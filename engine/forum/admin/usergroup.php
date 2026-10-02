<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: usergroup.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
	$subaction = $_GET['subaction'];

// ********************************************************************************
// Get User Groups
// ********************************************************************************	
	$user_group = array ();
	
	$db->query("SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC");
	
	while ($row = $db->get_row())
	{
		$user_group[$row['id']] = array ();
		
		foreach ($row as $key => $value)
		{
			$user_group[$row['id']][$key] = $value;
		}
	}
	
	$db->free();
	
// ********************************************************************************
// showRow
// ********************************************************************************
	function showRow($title="", $description="", $field="")
	{
		echo"<tr>
		<td style=\"padding:4px\" class=\"option\">
		<b>$title</b><br /><span class=small>$description</span>
		<td width=394 align=middle >$field
		</tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=2></td></tr>";
		$bg = ""; $i++;
	}
	
	switch ($subaction)
	{
// ********************************************************************************
// main
// ********************************************************************************
        case "":
		
		$db->query("SELECT user_group, count(*) as count FROM " . USERPREFIX . "_users GROUP BY user_group");
		
		while($row = $db->get_row())
		{
			$count_list[$row['user_group']] = $row['count'];
		}
		
		$db->free();
		
		foreach ($user_group as $group)
		{
			$count = intval ($count_list[$group['id']]);
			
			$entries .= "
			<tr>
			<td height=22 class=\"list\">&nbsp;&nbsp;<b>{$group['id']}</b></td>
			<td class=\"list\">{$group['group_name']}</td>
			<td class=\"list\" align=\"center\">$count</td>
			<td class=\"list\" align=\"center\"><a onClick=\"return dropdownmenu(this, event, MenuBuild('".$group['id']."'), '150px')\" href=\"#\"><img src=\"engine/skins/images/browser_action.gif\" border=\"0\"></a></td>
			</tr>
			<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";
		}
		
		echo_top();
		
echo <<<HTML
<script type="text/javascript" src="engine/classes/js/menu.js"></script>
<script language="javascript" type="text/javascript">
<!--
function MenuBuild( m_id ){

var menu=new Array()

menu[0]='<a href="?mod=forum&action=usergroup&subaction=edit&id=' + m_id + '" >{$f_lg['group_sel1']}</a>';
if (m_id > 5) {
menu[1]='<a href="?mod=usergroup&action=del&id=' + m_id + '" >{$f_lg['group_sel2']}</a>';
}
else {
menu[1]='<a href="#" onclick="return false;"><font color="black">{$f_lg['group_sel3']}</font></a>';
}

return menu;
}
//-->
</script>
HTML;
		
		echo_title($f_lg['group_list']);
		
		echo "<table width=\"100%\">
		<tr>
		<td width=50>&nbsp;&nbsp;ID</td>
		<td>{$f_lg['group_name']}</td>
		<td width=100 align=\"center\">{$f_lg['group_users']}</td>
		<td width=70 align=\"center\">&nbsp;</td>
		</tr>
		<tr><td colspan=\"4\"><div class=\"hr_line\"></div></td></tr>";
		
		echo $entries."</table>";
		
		echo_bottom();
		
		break;

// ********************************************************************************
// edit
// ********************************************************************************
		case "edit":
		
        $id = intval($_GET['id']);
        
		if ($id)
		{
			$row = $db->super_query("SELECT * FROM " . PREFIX . "_forum_groups LEFT JOIN " . PREFIX . "_forum_moderators ON " . PREFIX . "_forum_groups.group_id = " . PREFIX . "_forum_moderators.group_id WHERE " . PREFIX . "_forum_groups.group_id = '$id'");
			
			echo_top();
			
			echo_title ($f_lg['group_edit']." ".$user_group[$id]['group_name']);
			
			echo "<form action=\"{$PHP_SELF}?mod=forum&action=usergroup&subaction=doedit&id={$id}\" method=\"post\">";
			
			echo "<table width=\"100%\">";
			
			showRow($f_lg['group_colour'], $f_lg['group_colour_'], "<input class=edit type=text style=\"text-align: center;\" name='save[group_colour]' value='{$row['group_colour']}' size=40>");
			
			showRadio($f_lg['group_offline'], $f_lg[''], "offline", $row);
			
			showRadio($f_lg['group_post_edit'], $f_lg[''], "post_edit", $row);
			
			showRadio($f_lg['group_post_del'], $f_lg[''], "post_del", $row);
			
			showRadio($f_lg['group_topic_set'], $f_lg[''], "topic_set", $row);
			
			showRadio($f_lg['group_topic_edit'], $f_lg[''], "topic_edit", $row);
			
			showRadio($f_lg['group_topic_del'], $f_lg[''], "topic_del", $row);
			
			showRadio($f_lg['group_vote'], $f_lg[''], "vote", $row);
			
			showRadio($f_lg['group_flood'], $f_lg[''], "flood", $row);
			
			showRadio($f_lg['group_html'], $f_lg[''], "html", $row);
			
			showRadio($f_lg['group_filter'], $f_lg[''], "filter", $row);
            
            showRadio($f_lg['group_youtube'], $f_lg['group_youtube2'], "youtube", $row);
            
            showRadio($f_lg['group_flash'], $f_lg['group_flash2'], "flash", $row);
			
			echo "</table>";
			
			echo_bottom('bottom');
			
			echo_top('top');
			
			echo_title ($f_lg['group_moderation']);
			
			echo "<table width=\"100%\">";
			
			require_once ENGINE_DIR.'/forum/admin/moderation.php';
			
			echo "</table>";
			
			echo_bottom('bottom');
			
			echo_top('top');
			
			echo "<input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_save']}\"></form>";
			
			echo_bottom();
		}
		
		break;

// ********************************************************************************
// doedit
// ********************************************************************************
		case "doedit":
		
        $id   = intval($_GET['id']);
        
        $save = $_POST['save'];
        
		if ($id)
		{
			$get_group = $db->super_query("SELECT group_id FROM " . PREFIX . "_forum_groups WHERE group_id = '$id'");
			
			if (!$get_group['group_id'])
			{
				$db->query("INSERT INTO " . PREFIX . "_forum_groups (group_id) values ('$id')");
			}
			
			$db->query("UPDATE " . PREFIX . "_forum_groups SET group_colour = '$save[group_colour]', offline = '$save[offline]', post_edit = '$save[post_edit]', post_del = '$save[post_del]', topic_set = '$save[topic_set]', topic_edit = '$save[topic_edit]', topic_del = '$save[topic_del]', vote ='$save[vote]', flood = '$save[flood]', html = '$save[html]', filter = '$save[filter]', youtube = '$save[youtube]', flash = '$save[flash]' WHERE group_id = '$id'");
			
			$m_group = $db->super_query("SELECT group_id FROM " . PREFIX . "_forum_moderators WHERE group_id = '$id'");
			
			if (!$m_group['group_id'])
			{
				$db->query("INSERT INTO " . PREFIX . "_forum_moderators (group_id) values ('$id')");
			}
			
			$db->query("UPDATE " . PREFIX . "_forum_moderators SET edit_post = '$save[edit_post]', delete_topic = '$save[delete_topic]', edit_topic = '$save[edit_topic]', edit_post = '$save[edit_post]', delete_post = '$save[delete_post]', open_topic = '$save[open_topic]', close_topic = '$save[close_topic]', delete_post = '$save[delete_post]', move_topic = '$save[move_topic]', pin_topic = '$save[pin_topic]', delete_topic = '$save[delete_topic]', unpin_topic = '$save[unpin_topic]', allow_warn = '$save[allow_warn]', mass_prune = '$save[mass_prune]', combining_post = '$save[combining_post]', move_post = '$save[move_post]', read_mode = '$save[read_mode]', banned = '$save[banned]' WHERE group_id = '$id'");
		}
        
        $dle_forum->cache->delete('forum_groups');
        $dle_forum->cache->delete('forum_moderators');
		
		msg("info",$f_lg['group_edit_ok'], $f_lg['group_edit_ok2'], "?mod=forum");
		
		break;
	}
?>