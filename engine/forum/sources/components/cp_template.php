<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: cp_template.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

function echo_top($echoheader = false)
{
if (!$echoheader)
{
	echoheader("", "");
    
    if ($_REQUEST['action'])
    {
        echo echo_top (x);
        echo "<div class=\"quick\"><a href=\"?mod=forum\">DLE Forum</a></div>";
        echo echo_bottom (x);
    }
}

echo <<<HTML
  <div style="padding-top:5px;padding-bottom:2px;">
  <table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
HTML;
}

function echo_bottom($echofooter = false)
{
echo <<<HTML
  </td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
  </table>
  </div>
HTML;

if (!$echofooter) echofooter();
}

function echo_title($title)
{
echo <<<HTML
<table width="100%"><tr>
<td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$title}</div></td></tr></table>
<div class="unterline"></div>
HTML;
}

function echo_updates_js()
{
global $forum_config, $f_lg;

echo <<<HTML
<script language="javascript" type="text/javascript">
function check_forum_updates ( ){
	$('#update_box').show();
    $('#update_result').html('{$f_lg['check_updates_start']}');
    
    $.post('engine/forum/ajax/updates.php', {version_id: '{$forum_config['version_id']}'}, function(data) { $('#update_result').html(data); });

	return false;
}
</script>
HTML;

}

function js_forum_activation()
{
global $config, $f_lg;
	
echo <<<HTML
<script language="javascript" type="text/javascript">
function forum_activation (){

	var forum_key = $('#forum_key').val();
    
    $('#forum_key').html('{$f_lg['activation_send']}');
    
    $.post('{$config['admin_path']}?mod=forum', {forum_key: forum_key, forum_activation: 'yes'}, function(data) { $('#forum-activation').html(data); });

	return false;
}
</script>
HTML;
}

function activation_page()
{
    global $PHP_SELF, $f_lg;
    
    echo_top();
    
    echo_title($f_lg['copy_activate']);
    
    echo "<form method='post' action='{$PHP_SELF}?mod=forum'>
	<table border='0' width='100%'>
	<tr>
	<td width='260' height='25'>{$f_lg['copy_activate_id']}</td>
	<td><input class='edit' type='text' name='copy_id' size='27'></td></tr>
    <td width='260' height='25'>{$f_lg['copy_activate_pass']}</td>
	<td><input class='edit' type='password' name='copy_pass' size='27'></td></tr>
    <tr><td colspan='2'><div class='quick'>{$f_lg['copy_activate_info']}</div></td></tr>
	<tr><td colspan='2'><div class='hr_line'></div></td></tr>
	<tr>
	<td width='260'>&nbsp;</td>
	<td><input type='submit' class='buttons' value='{$f_lg['copy_activate_send']}'></td></tr>
	</table><input type='hidden' name='forum_activation' value='yes'/></form>";
    
    echo_bottom();
}

function echo_category($type, $name = false, $sid = false){
	global $f_lg;
	
	if ($type == "new")
	{
		$act = "category_save";
		$button = $f_lg['button_add'];
	}
	
	if ($type == "edit")
	{
		$act = "category_save&id={$sid}";
		$button = $f_lg['button_edit'];
	}
	
	echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=$act\">
	<table border=\"0\" width=\"100%\">
	<tr>
	<td width=\"260\">{$f_lg['cat_name']}</td>
	<td><input class=\"edit\" type=\"text\" name=\"name\" value=\"$name\" size=\"27\"></td></tr>
	<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
	<tr>
	<td width=\"260\">&nbsp;</td>
	<td><input type=\"submit\" class=\"buttons\" value=\"{$button}\"></td></tr>
	</table></form>";
}

function echo_forum($type, $id = false, $sub_id = false){
	global $db, $f_lg, $user_group, $dle_forum;
	
	if ($type == "new")
	{
		$act = "forum_save";
		
		$button = $f_lg['button_add'];
		
		$forum_list = $dle_forum->forum_list($sub_id);
		
		$access_forum = access_forum($user_group, '0');
		
		$f_form['postcount'] = 1;
	}
	
	if ($type == "edit")
	{
		include(ENGINE_DIR.'/classes/parse.class.php');
		
		$parse = new ParseFilter();
		
		$result = $db->query("SELECT * FROM " . PREFIX . "_forum_forums WHERE id = '$id'");
		
		while ($row = $db->get_row($result))
		{
			$f_form['name'] = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"),$row['name']));
			$f_form['description'] = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"),$row['description']));
			$f_form['password'] = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"),$row['password']));
			
			$f_form['rules_name'] = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"),$row['rules_title']));
			
			$f_form['rules'] = $parse->decodeBBCodes($row['rules'], false);
			
			$f_form['icon'] = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"),$row['icon']));
			
			$sel_id = $row['main_id'];
			
			$forum_id = $row['id'];
			
			$parentid = $row['parentid'];
			
			$f_form['postcount'] = $row['postcount'];
			
			$f_form['fixpost'] = $row['fixpost'];
            
            $f_form['banner'] = stripslashes($row['banner']);
            
            $f_form['q_reply'] = intval($row['q_reply']);
            
            $f_form['i_edit'] = intval($row['i_edit']);
            
            $f_form['is_category'] = intval($row['is_category']);
            
            $f_form['redirect'] = $row['redirect'];
		}
		
		$act = "forum_save&id=$forum_id";
		
		$button = $f_lg['button_edit'];
		
		$forum_list = $dle_forum->forum_list($parentid);
		
		$access_forum = access_forum($user_group, $forum_id);
	}
	
	echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=$act\">
	<fieldset><legend class=\"quick\"><strong>{$f_lg['forum_mset']}</strong></legend><br />
	<table border=\"0\" width=\"100%\">
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_name']}</b></td>
        <td><input class=\"edit\" type=\"text\" name=\"name\" value=\"{$f_form['name']}\" size=\"27\"></td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_descr']}</b></td>
        <td><TEXTAREA name=\"description\" rows=4 cols=60>{$f_form['description']}</TEXTAREA></td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_icon']}</b></td>
        <td><input class=\"edit\" type=\"text\" name=\"icon\" value=\"{$f_form['icon']}\" size=\"27\"><a href=\"#\" class=\"hintanchor\" onMouseover=\"showhint('{$f_lg[forum_icon_hint]}', this, event, '250px')\">[?]</a></td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['password']}</b></td>
        <td><input class=\"edit\" type=\"text\" name=\"password\" value=\"{$f_form['password']}\" size=\"27\"></td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_for']}</b></td>
        <td><SELECT name=\"parentid\">{$forum_list}</SELECT></td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['is_category']}</b><div class=\"quick\">{$f_lg['is_category_i']}</div></td>
        <td>".makeDropDown(array("1"=>$f_lg['yes'],"0"=>$f_lg['no']), "is_category", $f_form['is_category'])."</td>
    </tr>
    <tr><td colspan=\"2\"></td></tr></table></fieldset><br />
    
    <fieldset><legend class=\"quick\"><strong>{$f_lg['redirect']}</strong></legend><br />
	<table border=\"0\" width=\"100%\">
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['redirect_url']}</b><div class=\"quick\">{$f_lg['redirect_i']}</div></td>
        <td><input class=\"edit\" type=\"text\" name=\"redirect\" value=\"{$f_form['redirect']}\" size=\"27\"></td>
    </tr>
    <tr><td colspan=\"2\"></td></tr></table>
	</fieldset><br />
    
    <fieldset><legend class=\"quick\"><strong>{$f_lg['forum_posts_conf']}</strong></legend><br />
	<table border=\"0\" width=\"100%\">
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_postcount']}</b></td>
        <td>".makeDropDown(array("1"=>$f_lg['yes'],"0"=>$f_lg['no']), "postcount", $f_form['postcount'])."</td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_fixpost']}</b></td>
        <td>".makeDropDown(array("1"=>$f_lg['yes'],"0"=>$f_lg['no']), "fixpost", $f_form['fixpost'])."</td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_q_reply']}</b></td>
        <td>".makeDropDown(array("1"=>$f_lg['yes'],"0"=>$f_lg['no']), "q_reply", $f_form['q_reply'])."</td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_i_edit']}</b></td>
        <td>".makeDropDown(array("1"=>$f_lg['yes'],"0"=>$f_lg['no']), "i_edit", $f_form['i_edit'])."</td>
    </tr>
    <tr><td colspan=\"2\"></td></tr></table>
	</fieldset><br />
    
    <fieldset><legend class=\"quick\"><strong>{$f_lg['forum_rules']}</strong></legend><br />
	<table border=\"0\" width=\"100%\">
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_rules1']}</b></td>
        <td><input class=\"edit\" type=\"text\" name=\"rules_name\" value=\"{$f_form['rules_name']}\" size=\"27\"></td>
    </tr>
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_rules2']}</b></td>
        <td><TEXTAREA name=\"rules\" rows=10 cols=60>{$f_form['rules']}</TEXTAREA></td>
    </tr>
    <tr><td colspan=\"2\"></td></tr></table>
	</fieldset><br />
    
    <fieldset><legend class=\"quick\"><strong>{$f_lg['forum_banner']}</strong></legend><br />
	<table border=\"0\" width=\"100%\">
    <tr>
        <td style=\"padding:4px\" class=\"option\" width=\"260\" height=\"25\"><b>{$f_lg['forum_banner2']}</b></td>
        <td><TEXTAREA name=\"banner\" rows=10 cols=60>{$f_form['banner']}</TEXTAREA></td>
    </tr>
    <tr><td colspan=\"2\"></td></tr></table>
	</fieldset><br />
    
    <fieldset><legend class=\"quick\"><strong>{$f_lg['forum_access']}</strong></legend><br />{$access_forum}</fieldset>
    <table border=\"0\" width=\"100%\">
    <tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
    <tr>
        <td width=\"260\">&nbsp;</td>
        <td><input type=\"submit\" class=\"buttons\" value=\"{$button}\"></td></tr>
		</table></form>";
}

function echo_stats($forum_config, $forum_stats)
{
global $f_lg;

echo "<table width=\"100%\">
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_version']}</td><td>{$forum_config['version_id']}</td></tr>
<tr>
<td width=\"265\" style=\"padding:2px;\">{$f_lg['licence_info']}</td><td>{$forum_stats['licence']}</td>
</tr>
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_status']}</td><td>{$forum_stats['line']}</td>
</tr>
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_topic']}</td><td>{$forum_stats['topics']}</td>
</tr>
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_posts']}</td><td>{$forum_stats['posts']}</td>
</tr>
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_db_size']}</td><td>{$forum_stats['db_size']}</td>
</tr>
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_files']}</td><td>{$forum_stats['sum_size']}</td>
</tr>
<tr>
<td style=\"padding:2px;\">{$f_lg['forum_cache']}</td><td>{$forum_stats['cache']}</td>
</tr>
</table>
<br />
<input onclick=\"check_forum_updates(); return false;\" class=\"edit\" style=\"width:205px;\" type=\"button\" value=\"{$f_lg['check_updates']}\">&nbsp;
<input onclick=\"window.open('http://dlekey.cn/'); return false;\" class=\"edit\" style=\"width:120px;\" type=\"button\" value=\"DLE Files Group\">&nbsp;
<a href=\"?mod=forum&action=clear\"><input onclick=\"document.location='?mod=forum&action=clear'\" class=\"edit\" style=\"width:150px;\" type=\"button\" value=\"{$f_lg['clear_cache']}\"></a>";
}

function echo_rank($type, $id = false){
	global $db, $f_lg;
	
	if ($type == "new")
	{
		$act = "rank_add";
		$button = $f_lg['button_add'];
	}
	
	if ($type == "edit")
	{
		$act = "rank_save&id=$id";
		$button = $f_lg['button_edit'];
		
		$result = $db->super_query("SELECT * FROM " . PREFIX . "_forum_titles WHERE id = '$id'");
		
		$t_name = $result['title'];
		
		$t_posts = $result['posts'];
		
		$t_pips = $result['pips'];
	}
	
	echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=$act\">
	<table border=\"0\" width=\"100%\">
	<tr>
	<td width=\"260\">{$f_lg['titles_nname']}</td>
	<td><input class=\"edit\" type=\"text\" name=\"t_name\" value=\"$t_name\" size=\"27\"></td></tr>
	<tr>
	<td width=\"260\">{$f_lg['titles_npost']}</td>
	<td><input class=\"edit\" type=\"text\" name=\"t_posts\" value=\"$t_posts\" size=\"27\"></td></tr>
	<tr>
	<td width=\"260\">{$f_lg['titles_npips']}</td>
	<td><input class=\"edit\" type=\"text\" name=\"t_pips\" value=\"$t_pips\" size=\"27\"></td></tr>
	<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
	<tr>
	<td width=\"260\">&nbsp;</td>
	<td><input type=\"submit\" class=\"buttons\" value=\"{$button}\"></td></tr>
	</table></form>";
}

if(!defined('DLE_FORUM')){ exit; }

function echo_mail($name, $text, $description)
{
	echo "<table width=\"100%\">
	<tr>
	<td style=\"padding:2px;\">{$description}</td>
	</tr>
	<tr>
	<td style=\"padding:2px;\"><textarea rows=\"15\" style=\"width:650px;\" name=\"{$name}\">{$text}</textarea></td>
	</tr>
	</table>";
}

function echo_rank_style ()
{
 global $config;
	$style =  <<<HTML
<style type="text/css" media="all">
.rank {

	width: 85px;

	height: 16px;

}

.unit-rank {

	list-style: none;

	margin: 0px;

	padding: 0px;

	width: 85px;

	height: 16px;

	position: relative;

	background-image: url('{THEME}/forum/images/rating.gif');

	background-position: top left;

	background-repeat: repeat-x;

}

.unit-rank li {

	text-indent: -90000px;

	padding: 0px;

	margin: 0px;

	float: left;

}

.unit-rank li a {

	display: block;

	width: 17px;

	height: 16px;

	text-decoration: none;

	text-indent: -9000px;

	z-index: 17;

	position: absolute;

	padding: 0px;

}

.unit-rank li a:hover {

	background-image: url('{THEME}/forum/images/rating.gif');

	background-position: left center;

	z-index: 2;

	left: 0px;

}

.unit-rank a.r1-unit { left: 0px; }

.unit-rank a.r1-unit:hover { width: 17px; }

.unit-rank a.r2-unit { left: 17px; }

.unit-rank a.r2-unit:hover { width: 34px; }

.unit-rank a.r3-unit { left: 34px; }

.unit-rank a.r3-unit:hover { width: 51px; }

.unit-rank a.r4-unit { left: 51px; }

.unit-rank a.r4-unit:hover { width: 68px; }

.unit-rank a.r5-unit { left: 68px; }

.unit-rank a.r5-unit:hover { width: 85px; }

.unit-rank li.current-rank {

	background-image: url('{THEME}/forum/images/rating.gif');

	background-position: left bottom;

	position: absolute;

	height: 16px;

	display: block;

	text-indent: -9000px;

	z-index: 1;

}
</style>
HTML;

echo str_replace('{THEME}', $config['http_home_url'].'templates/'.$config['skin'], $style);

}

function forum_menu()
{
global $f_lg;

echo <<<HTML
<script type="text/javascript" src="engine/classes/js/menu.js"></script>
<script language="javascript" type="text/javascript">

function MenuCategory( m_id ){

var menu=new Array()
var lang_action = "";

menu[0]='<a onClick="document.location=\'?mod=forum&action=forum&parent_id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_add_forum']}</a>';
menu[1]='<a onClick="document.location=\'?mod=forum&action=content_forum&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_sort_f']}</a>';
menu[3]='<a onClick="document.location=\'?mod=forum&action=category_edit&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_edit']}</a>';
menu[4]='<a onClick="document.location=\'?mod=forum&action=forum_del&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_del']}</a>';

return menu;
}

function MenuForum( m_id, sub ){

var menu=new Array()

menu[1]='<a onClick="document.location=\'?mod=forum&action=moderator_new&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_moderator']}</a>';

menu[2]='<a onClick="document.location=\'?mod=forum&action=forum_edit&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_edit']}</a>';

if (!sub){
menu[3]='<a onClick="document.location=\'?mod=forum&action=content_forum&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_ssort']}</a>';
}

menu[4]='<a onClick="document.location=\'?mod=forum&action=forum_del&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_del']}</a>';

return menu;
}

function Moderators( m_id ){

var menu=new Array()

menu[1]='<a onClick="document.location=\'?mod=forum&action=moderator_edit&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_edit']}</a>';

menu[2]='<a onClick="document.location=\'?mod=forum&action=moderator_del&id=' + m_id + '\'; return(false)" href="#">{$f_lg['java_del']}</a>';

return menu;
}

</script>

HTML;
}

$options = array(
                    array(
                    'name'       => $f_lg['m_new_cat'],
                    'url'        => "$PHP_SELF?mod=forum&action=category",
					'descr'      => $f_lg['m_new_cat2'],
					'image'      => "category.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_new_forum'],
                    'url'        => "$PHP_SELF?mod=forum&action=forum",
					'descr'      => $f_lg['m_new_forum2'],
					'image'      => "forum.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_content'],
                    'url'        => "$PHP_SELF?mod=forum&action=content",
					'descr'      => $f_lg['m_content2'],
					'image'      => "content.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_discuss'],
                    'url'        => "$PHP_SELF?mod=forum&action=discuss",
					'descr'      => $f_lg['m_discuss2'],
					'image'      => "discuss.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_usergroup'],
                    'url'        => "$PHP_SELF?mod=forum&action=usergroup",
					'descr'      => $f_lg['m_usergroup2'],
					'image'      => "usersgroup.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_rank'],
                    'url'        => "$PHP_SELF?mod=forum&action=rank",
					'descr'      => $f_lg['m_rank2'],
					'image'      => "rank.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_tools'],
                    'url'        => "$PHP_SELF?mod=forum&action=tools",
					'descr'      => $f_lg['m_tools2'],
					'image'      => "tools.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_service'],
                    'url'        => "$PHP_SELF?mod=forum&action=service",
					'descr'      => $f_lg['m_service2'],
					'image'      => "service.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_email'],
                    'url'        => "$PHP_SELF?mod=forum&action=email",
					'descr'      => $f_lg['m_email2'],
					'image'      => "mset.png",
                    'access'     => "1",
                    ),
                    
                    array(
                    'name'       => $f_lg['m_help'],
                    'url'        => "$PHP_SELF?mod=forum&action=help",
					'descr'      => $f_lg['m_help2'],
					'image'      => "help.png",
                    'access'     => "1",
                    ),
                    
                    );
                    
?>