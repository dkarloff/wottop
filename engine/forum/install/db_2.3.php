<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db_2.3.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_posts ADD `wysiwyg` TINYINT( 1 ) DEFAULT '0'";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums CHANGE `f_last_title` `f_last_title` VARCHAR(70)";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_time` VARCHAR( 20 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE  " . PREFIX . "_forum_topics ADD  `icon` VARCHAR( 20 ) DEFAULT  '0'";
	
?>