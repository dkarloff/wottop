<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db_2.5.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `last_post_id` INT( 11 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics ADD `last_post_id` INT( 11 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_groups ADD `youtube` TINYINT( 1 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_groups ADD `flash` TINYINT( 1 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_moderators ADD `combining_post` TINYINT( 1 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_moderators ADD `move_post` TINYINT( 1 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `banner` TEXT NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `q_reply` TINYINT( 1 ) DEFAULT '1' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `i_edit` TINYINT( 1 ) DEFAULT '0' NOT NULL";
	
?>