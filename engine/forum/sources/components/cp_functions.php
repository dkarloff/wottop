<?php if(!defined('DLE_FORUM')) { exit; }
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: cp_functions.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

// ********************************************************************************
// ACCESS
// ********************************************************************************
function access_forum($user_group, $forum_id){
	global $access_forum, $db, $f_lg;
	
	$access_mod = array();
	
	$access_topic = array();
	
	$access_write = array();
	
	$access_read = array();
	
	$access_upload = array();
	
	$access_download = array();
	
	// JavaScript by ShVad //
	$access_forum = <<<HTML
	<script language='JavaScript' type="text/javascript">

function access_check( id, value )
{
	if (value == 'y'){ value = 1; } else { value = 0; }
	
	document.getElementById( 'access_mod' + '[' + id + ']' ).checked = value;
	
	document.getElementById( 'access_topic' + '[' + id + ']' ).checked = value;
	
	document.getElementById( 'access_write' + '[' + id + ']' ).checked = value;
	
	document.getElementById( 'access_read' + '[' + id + ']' ).checked = value;
	
	document.getElementById( 'access_upload' + '[' + id + ']' ).checked = value;
	
	document.getElementById( 'access_download' + '[' + id + ']' ).checked = value;
}

</script>
HTML;
	
	if ($forum_id)
	{
		$access_result = $db->query("SELECT * FROM " . PREFIX . "_forum_forums WHERE id = '$forum_id'");
		
		while ($row = $db->get_row($access_result))
		{
			$access_mod = $row['access_mod'];
			
			$access_topic = $row['access_topic'];
			
			$access_write = $row['access_write'];
			
			$access_read = $row['access_read'];
			
			$access_upload = $row['access_upload'];
			
			$access_download = $row['access_download'];
		}
	}
	
	$access_forum .= "<table width='100%'><tr>
        <td class=\"option\" width='16%'><left><b>{$f_lg['access_forum_group']}</b></left></td>
        <td class=\"option\" width='10%'><center><b>{$f_lg['access_forum_mod']}</b></center></td>
        <td class=\"option\" width='14%'><center><b>{$f_lg['access_forum_topic']}</b></center></td>
        <td class=\"option\" width='14%'><center><b>{$f_lg['access_forum_write']}</b></center></td>
        <td class=\"option\" width='14%'><center><b>{$f_lg['access_forum_read']}</b></center></td>
        <td class=\"option\" width='14%'><center><b>{$f_lg['access_forum_upload']}</b></center></td>
        <td class=\"option\" width='16%'><center><b>{$f_lg['access_forum_download']}</b></center></td>
		</tr></table>
		<div class='hr_line'></div>";
		
	$access_forum .= "<table width='100%'>";

    foreach($user_group as $id => $group_name)
	{
		if ($forum_id)
		{
		$access_mod_array = explode(":",$access_mod);
		if (in_array($id, $access_mod_array)){$checked1 = "checked";}else{$checked1 = "";}
		
		$access_topic_array = explode(":",$access_topic);
		if (in_array($id, $access_topic_array)){$checked2 = "checked";}else{$checked2 = "";}
		
		$access_write_array = explode(":",$access_write);
		if (in_array($id, $access_write_array)){$checked3 = "checked";}else{$checked3 = "";}
		
		$access_read_array = explode(":",$access_read);
		if (in_array($id, $access_read_array)){$checked4 = "checked";}else{$checked4 = "";}
		
		$access_upload_array = explode(":",$access_upload);
		if (in_array($id, $access_upload_array)){$checked5 = "checked";}else{$checked5 = "";}
		
		$access_download_array = explode(":",$access_download);
		if (in_array($id, $access_download_array)){$checked6 = "checked";}else{$checked6 = "";}
		}
		
		$access_forum .= "<tr>
	    <td style=\"padding:4px\" class=\"option\" width='16%'>
		<left>{$group_name[group_name]} [<a href=\"javascript:access_check('$id', 'y')\">+</a> |
		                                                 <a href=\"javascript:access_check('$id', 'n')\">-</a>]</left>
	    </td>
        <td style=\"padding:4px\" class=\"option\" width='10%'>
		<center><input type='checkbox' name='access_mod[$id]' id='access_mod[$id]' value='$id' $checked1></center></td>
        <td style=\"padding:4px\" class=\"option\" width='14%'>
		<center><input type='checkbox' name='access_topic[$id]' id='access_topic[$id]' value='$id' $checked2></center></td>
        <td style=\"padding:4px\" class=\"option\" width='14%'>
		<center><input type='checkbox' name='access_write[$id]' id='access_write[$id]' value='$id' $checked3></center></td>
        <td style=\"padding:4px\" class=\"option\" width='14%'>
		<center><input type='checkbox' name='access_read[$id]' id='access_read[$id]' value='$id' $checked4></center></td>
        <td style=\"padding:4px\" class=\"option\" width='14%'>
		<center><input type='checkbox' name='access_upload[$id]' id='access_upload[$id]' value='$id' $checked5></center></td>
        <td style=\"padding:4px\" class=\"option\" width='16%'>
		<center><input type='checkbox' name='access_download[$id]' id='access_download[$id]' value='$id' $checked6></center></td>
		</tr>";
	}
        
    $access_forum .= "</table>";
    
    return($access_forum);
}

define('DLEFORUM', true);

// ********************************************************************************
// Category and Forum POSITION
// ********************************************************************************
function category_posi($category_posi, $category_id, $result_posi){
	global $db;
	
	$cat_posi = array();
	
	$select_posi = "<select name=\"cat_posi[$category_id]\">";
	
	for($j=1; $j<=$result_posi; $j++)
	{
		if ($j == $category_posi)
		{
			$select_posi .= "<option value='$j' selected>$j</option>";
		}
		
		else
		{
			$select_posi .= "<option value='$j'>$j</option>";
		}
	}
	
	$select_posi .= "</select>";
	
	return ($select_posi);
}

function position_count ($parent_id = '-1')
{
    global $db;
    
    $row = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_forum_forums WHERE parentid = '{$parent_id}'");
    
    return $row['count'];
}

// ********************************************************************************
// SELECT USER GROUP
// ********************************************************************************
function warn_group($warn_group = false){
	global $db;
	
	if ($warn_group)
	{
		$warn_list = explode (':', $warn_group);
	}
	
	$db->query("SELECT * FROM " . USERPREFIX . "_usergroups GROUP BY id");
	
	$group_list .= "<option value=\"\"></option>";

	while($row = $db->get_row())
	{	
		if ($warn_group)
		{
			if (in_array($row['id'], $warn_list))
			{
				$selected = "selected";
			} else 
			{
				$selected = "";
			}
		}
		
		$group_list .= "<option value=\"{$row['id']}\" {$selected}>{$row['group_name']}</option>";
	}
	
	return ($group_list);
}

// ********************************************************************************
// Show Radio
// ********************************************************************************
function showRadio($title = "", $description = "", $allow_name = "", $row = false){
	 global $f_lg;
	 
		if ($row[$allow_name])
		{
			$o_value = "checked";
			
			$t_value = "";
		}
		
		else
		{
			$o_value = "";
			
			$t_value = "checked";
		}
		
		echo"<tr>
		<td style=\"padding:4px\" class=\"option\">
		<b>{$title}</b><br /><span class=small>{$description}</span>
		<td width=394 align=middle >
		<input type=\"radio\" name=\"save[{$allow_name}]\" {$o_value} value=\"1\">{$f_lg['yes']}
		<input type=\"radio\" name=\"save[{$allow_name}]\" {$t_value} value=\"0\">{$f_lg['no']}
		</tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=2></td></tr>";
	}
	
// ********************************************************************************
// makeDropDown
// ********************************************************************************
	function makeDropDown($options, $name, $selected)
	{
		$output = "<select name=\"$name\">\r\n";
			
		foreach($options as $value => $description)
		{
			$output .= "<option value=\"$value\"";
			if($selected == $value){ $output .= " selected "; }
			$output .= ">$description</option>\n";
		}
			
		$output .= "</select>";
			
		return $output;
	}

// ********************************************************************************
// Dir Size
// ********************************************************************************
function dirsize($directory)
  {
    if(!defined('DLE_FORUM_CACHE')) { exit; }

     if (!is_dir($directory)) return -1;

     $size = 0;

     if ($DIR = opendir($directory))
     {
        while (($dirfile = readdir($DIR)) !== false)
        {

           if (@is_link($directory . '/' . $dirfile) || $dirfile == '.' || $dirfile == '..')
              continue;

           if (@is_file($directory . '/' . $dirfile))
              $size += filesize($directory . '/' . $dirfile);

           else if (@is_dir($directory . '/' . $dirfile))
           {

             $dirSize = dirsize($directory . '/' . $dirfile);
              if ($dirSize >= 0) $size += $dirSize;
              else return -1;

           }

        }

        closedir($DIR);

     }

     return $size;

  }

?>