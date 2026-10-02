<?php if(!defined('DATALIFEENGINE')) { die("Hacking attempt!"); }
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: discuss.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

	$subaction = $_GET['subaction'];
	
	switch ($subaction)
	{
		case "":
		
		echo_top();
		
		echo_title($f_lg['discuss_name']);
		
		$category = $db->query("SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC");
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=discuss&subaction=save\">";
		
		echo "<table width=\"100%\" border=\"0\"><tr>
        <td style=\"padding:2px;\" width=\"25%\" height=\"22\"><center>{$f_lg['discuss_cat_id']}</center></td>
        <td width=\"25%\" height=\"24\"><center>{$f_lg['discuss_category']}</center></td>
        <td width=\"25%\" height=\"24\"><center>{$f_lg['discuss_forum_id']}</center></td>
        <td width=\"25%\" height=\"24\"><center>{$f_lg['discuss_forum']}</center></td></tr>
		</table><div class='unterline'></div><table width=\"100%\" border=\"0\">";
		
		while($row = $db->get_row($category))
		{
			if (!$row['forum_id'])
			{
				$row['forum_id'] = "-";
			}
			
			$forum_list = FALSE;
			
			echo "<tr>
			<td style=\"padding:2px;\" width=\"25%\" height=\"22\"><center>{$row['id']}</center></td>
			<td width=\"25%\" height=\"22\"><center>{$row['name']}</center></td>
			<td width=\"25%\" height=\"22\"><center>{$row['forum_id']}</center></td>
			<td width=\"25%\" height=\"22\"><center>
			<SELECT name=\"category_id[{$row['id']}]\">".$dle_forum->forum_list($row['forum_id'], true)."</SELECT>
			</center></td></tr>
			<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>";
		}
		
		echo "</table>";
		
		echo "<br /><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_save']}\"></form>";
		
		echo_bottom();
		
		break;
		
		case "save":
		
        $category_id = $_POST['category_id'];
        
		$category = $db->query("SELECT * FROM " . PREFIX . "_category");
		
		while($row = $db->get_row($category))
		{
			$db->query("UPDATE " . PREFIX . "_category SET forum_id = '".$category_id[$row['id']]."' WHERE id = '$row[id]'");
		}
		
		header("Location: ?mod=forum&action=discuss");
		
		break;
	}
	
?>