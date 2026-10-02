<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db_2.4.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics ADD `meta_descr` VARCHAR(200) DEFAULT '0'";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics ADD `meta_keywords` TEXT DEFAULT '0'";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `postcount` TINYINT(1) DEFAULT '1' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_posts ADD  `is_count` TINYINT( 1 ) DEFAULT  '1' NOT NULL";

	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `fixpost` TINYINT( 1 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics ADD `first_post` INT( 11 ) DEFAULT '0' NOT NULL";
	
?>