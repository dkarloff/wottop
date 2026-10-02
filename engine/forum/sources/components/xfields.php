<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: xfields.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
	$xfieldsdata = xfieldsdataload ($row['xfields']);
	
	foreach ($xfields as $value)
	{
		$preg_safe_name = preg_quote($value[0], "'");
		
		if ($value[5] != 1 OR $member_id['user_group'] == 1 OR ($is_logged AND $row['is_register'] AND $member_id['name'] == $row['name']))
		{
			if (empty($xfieldsdata[$value[0]]))
			{
				$tpl->copy_template = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template);
			}
			
			else
			{
				$tpl->copy_template = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $tpl->copy_template);
			}
			
			$tpl->copy_template = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", stripslashes($xfieldsdata[$value[0]]), $tpl->copy_template);
		}
		
		else
		{
			$tpl->copy_template = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", "", $tpl->copy_template);
		}
	}
?>