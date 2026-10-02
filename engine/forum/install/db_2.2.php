<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db_2.2.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_groups (
	`group_id` int(11) NOT NULL default '0',
	`group_colour` varchar(40) NOT NULL default '',
	`offline` tinyint(1) NOT NULL default '0',
	`post_edit` tinyint(1) NOT NULL default '0',
	`post_del` tinyint(1) NOT NULL default '0',
	`topic_set` tinyint(1) NOT NULL default '0',
	`topic_edit` tinyint(1) NOT NULL default '0',
	`topic_del` tinyint(1) NOT NULL default '0',
	`vote` tinyint(1) NOT NULL default '0',
	`flood` char(1) NOT NULL default '0',
	`html` tinyint(1) NOT NULL default '0',
	`filter` tinyint(1) NOT NULL default '0',
	KEY `group_id` (`group_id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
    
    $db_query[] = "CREATE TABLE " . PREFIX . "_forum_reputation_log (
	`rid` int(11) NOT NULL auto_increment,
	`mid` varchar(8) NOT NULL default '0',
	`author` varchar(40) NOT NULL,
	`action` char(1) NOT NULL,
	`cause` text NOT NULL,
	`date` int(10) NOT NULL default '0',
	PRIMARY KEY  (`rid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_moderators (
	`mid` mediumint(8) NOT NULL auto_increment,
	`forum_id` int(11) NOT NULL default '0',
	`member_name` varchar(32) NOT NULL default '',
	`member_id` mediumint(8) NOT NULL default '0',
	`edit_post` tinyint(1) default NULL,
	`edit_topic` tinyint(1) default NULL,
	`delete_post` tinyint(1) default NULL,
	`delete_topic` tinyint(1) default NULL,
	`open_topic` tinyint(1) NOT NULL default '0',
	`close_topic` tinyint(1) default NULL,
	`mass_prune` tinyint(1) default NULL,
	`move_topic` tinyint(1) default NULL,
	`pin_topic` tinyint(1) default NULL,
	`unpin_topic` tinyint(1) default NULL,
	`allow_warn` tinyint(1) default NULL,
	`is_group` tinyint(1) default '0',
	`group_id` smallint(3) default NULL,
	PRIMARY KEY  (`mid`),
	KEY `forum_id` (`forum_id`),
	KEY `group_id` (`group_id`),
	KEY `member_id` (`member_id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_subscription (
	`sid` int(11) NOT NULL auto_increment,
	`user_id` mediumint(8) NOT NULL default '0',
	`topic_id` int(10) NOT NULL default '0',
	PRIMARY KEY  (`sid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_views (
	`topic_id` int(11) NOT NULL default '0',
	`forum_id` int(11) NOT NULL default '0',
	`user_id` mediumint(8) NOT NULL default '0',
	`time` int(11) default NULL
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_warn_log (
	`wid` int(11) NOT NULL auto_increment,
	`mid` varchar(8) NOT NULL default '0',
	`author` varchar(40) NOT NULL,
	`action` char(1) NOT NULL,
	`cause` text NOT NULL,
	`date` int(10) NOT NULL default '0',
	PRIMARY KEY  (`wid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `moderators` varchar(150) NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_reputation` smallint(5) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_last` varchar(20) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_sessions ADD `user_group` int(11) DEFAULT '5' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_posts ADD `edit_user` varchar(40) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_posts ADD `edit_time` int(10) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_posts ADD `hidden` tinyint(1) DEFAULT '0' NOT NULL";
    
    // INSERT //
    $db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (1, 'red', 1, 1, 1, 1, 1, 1, 1, '0', 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (2, 'blue', 0, 1, 1, 1, 1, 1, 1, '0', 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (3, '', 0, 1, 1, 1, 0, 0, 1, '1', 0, 1)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (4, '', 0, 1, 1, 0, 0, 0, 1, '1', 0, 1)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (5, '', 0, 0, 0, 0, 0, 0, 0, '0', 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (1, 0, '', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (2, 0, '', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 2)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (3, 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 3)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (4, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (5, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5)";
	
	// CHANGE //
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_category CHANGE `sid` `sid` int(11) NOT NULL AUTO_INCREMENT";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_email CHANGE `id` `id` int(11) NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `file_id` `file_id` int(11) NOT NULL AUTO_INCREMENT";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `forum_id` `forum_id` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `topic_id` `topic_id` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `post_id` `post_id` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `file_attach` `file_attach` tinyint(1) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `file_date` `file_date` int(10) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `file_size` `file_size` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_files CHANGE `dcount` `dcount` int(11) DEFAULT '0' NOT NULL";
	
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums CHANGE `id` `id` int(11) NOT NULL AUTO_INCREMENT";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums CHANGE `parentid` `parentid` int(11) NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums CHANGE `main_id` `main_id` int(11) NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_titles CHANGE `id` `id` int(11) NOT NULL AUTO_INCREMENT";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_titles CHANGE `posts` `posts` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics CHANGE `forum_id` `forum_id` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics CHANGE `post` `post` int(11) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics CHANGE `views` `views` int(11) DEFAULT '0' NOT NULL";
	
?>