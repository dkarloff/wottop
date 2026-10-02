<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db_query = array();	
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_forums";
    
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_forums (
    `id` int(11) NOT NULL auto_increment,
    `parentid` int(11) NOT NULL default '0',
    `is_category` TINYINT(1) NOT NULL default '0',
    `alt_name` varchar(50) NOT NULL default '',
    `topics` mediumint(6) NOT NULL default '0',
    `posts` mediumint(6) NOT NULL default '0',
    `name` varchar(128) NOT NULL default '',
    `description` text NOT NULL,
    `position` tinyint(3) NOT NULL default '0',
    `status` tinyint(1) NOT NULL default '1',
    `access_read` varchar(150) NOT NULL default '',
    `access_write` varchar(150) NOT NULL default '',
    `access_mod` varchar(150) NOT NULL default '',
    `access_topic` varchar(150) NOT NULL default '',
    `access_upload` varchar(150) NOT NULL default '',
    `access_download` varchar(150) NOT NULL default '',
    `f_last_tid` smallint(5) NOT NULL default '0',
    `f_last_title` varchar(70) NOT NULL default '',
    `f_last_date` datetime NOT NULL default '0000-00-00 00:00:00',
    `f_last_poster_name` varchar(40) NOT NULL default '',
    `password` varchar(32) NOT NULL default '',
    `rules_title` varchar(128) NOT NULL default '',
    `rules` text NOT NULL,
    `icon` varchar(40) NOT NULL default '',
    `moderators` varchar(150) NOT NULL default '',
    `postcount` tinyint(1) NOT NULL default '1',
    `fixpost` tinyint(1) NOT NULL default '0',
    `last_post_id` int(11) NOT NULL default '0',
    `banner` text NOT NULL,
    `q_reply` tinyint(1) NOT NULL default '1',
    `i_edit` tinyint(1) NOT NULL default '0',
    `redirect` varchar(250) NOT NULL default '',
    PRIMARY KEY  (`id`)
    )TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_topics";
    
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_topics (
	`tid` int(11) NOT NULL auto_increment,
	`forum_id` int(11) NOT NULL default '0',
    `alt_name` varchar(200) NOT NULL default '',
	`title` varchar(70) NOT NULL default '',
	`topic_descr` varchar(70) NOT NULL default '',
	`icon` varchar(20) default '0',
	`post` int(11) NOT NULL default '0',
	`views` int(11) NOT NULL default '0',
	`author_topic` varchar(40) NOT NULL default '',
	`start_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`last_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`last_poster_name` varchar(40) NOT NULL default '',
	`topic_status` int(1) NOT NULL default '0',
	`hidden` int(1) NOT NULL default '0',
	`fixed` int(1) NOT NULL default '1',
	`poll_title` varchar(200) NOT NULL default '',
	`frage` varchar(200) NOT NULL default '',
	`poll_body` text NOT NULL,
	`poll_count` mediumint(8) NOT NULL default '0',
	`answer` varchar(150) NOT NULL default '',
	`multiple` tinyint(1) NOT NULL default '0',
	`meta_descr` varchar(200) default NULL,
	`meta_keywords` text,
	`first_post` int(11) NOT NULL default '0',
    `last_post_id` int(11) NOT NULL default '0',
	PRIMARY KEY  (`tid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_posts";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_posts (
	`pid` int(11) NOT NULL auto_increment,
	`topic_id` int(11) NOT NULL default '0',
	`post_date` datetime NOT NULL default '0000-00-00 00:00:00',
	`post_author` varchar(40) NOT NULL default '',
	`post_text` text NOT NULL,
	`post_ip` varchar(16) NOT NULL default '',
	`is_register` tinyint(1) NOT NULL default '0',
	`e_mail` varchar(40) NOT NULL default '',
	`edit_user` varchar(40) NOT NULL default '0',
	`edit_time` int(10) NOT NULL default '0',
	`hidden` tinyint(1) NOT NULL default '0',
	`wysiwyg` tinyint(1) default '0',
	`is_count` tinyint(1) NOT NULL default '1',
	PRIMARY KEY  (`pid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_email";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_email (
	`id` int(11) NOT NULL default '0',
	`name` varchar(40) NOT NULL default '',
	`template` text NOT NULL,
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_files";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_files (
	`file_id` int(11) NOT NULL auto_increment,
	`file_type` varchar(10) NOT NULL default '',
	`forum_id` int(11) NOT NULL default '0',
	`topic_id` int(11) NOT NULL default '0',
	`post_id` int(11) NOT NULL default '0',
	`file_attach` tinyint(1) NOT NULL default '0',
	`file_name` varchar(250) NOT NULL default '',
	`onserver` varchar(250) NOT NULL default '',
	`file_author` varchar(40) NOT NULL default '',
	`file_date` int(10) NOT NULL default '0',
	`file_size` int(11) NOT NULL default '0',
	`dcount` int(11) NOT NULL default '0',
	PRIMARY KEY  (`file_id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_groups";
	
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
    `youtube` tinyint(1) NOT NULL default '0',
    `flash` tinyint(1) NOT NULL default '0',
	KEY `group_id` (`group_id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_poll_log";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_poll_log (
	`id` int(11) unsigned NOT NULL auto_increment,
	`topic_id` int(10) unsigned NOT NULL default '0',
	`member` varchar(30) NOT NULL default '',
	PRIMARY KEY  (`id`),
	KEY `news_id` (`topic_id`,`member`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_reputation_log";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_reputation_log (
	`rid` int(11) NOT NULL auto_increment,
	`mid` varchar(8) NOT NULL default '0',
	`author` varchar(40) NOT NULL,
	`action` char(1) NOT NULL,
	`cause` text NOT NULL,
	`date` int(10) NOT NULL default '0',
	PRIMARY KEY  (`rid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_sessions";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_sessions (
	`id` varchar(60) NOT NULL default '0',
	`member_name` varchar(64) NOT NULL default '',
	`user_group` int(11) NOT NULL default '5',
	`member_id` mediumint(8) NOT NULL default '0',
	`ip` varchar(16) NOT NULL default '',
	`browser` varchar(200) NOT NULL default '',
	`running_time` int(10) NOT NULL default '0',
	`location` varchar(40) NOT NULL default '',
	`act_index` int(10) NOT NULL default '0',
	`act_forum` int(10) NOT NULL default '0',
	`act_topic` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	KEY `act_topic` (`act_topic`),
	KEY `act_forum` (`act_forum`),
	KEY `act_index` (`act_index`),
	KEY `running_time` (`running_time`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_subscription";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_subscription (
	`sid` int(11) NOT NULL auto_increment,
	`user_id` mediumint(8) NOT NULL default '0',
	`topic_id` int(10) NOT NULL default '0',
	PRIMARY KEY  (`sid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_titles";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_titles (
	`id` int(11) NOT NULL auto_increment,
	`posts` int(11) NOT NULL default '0',
	`title` varchar(128) NOT NULL default '',
	`pips` varchar(128) NOT NULL default '',
	KEY `id` (`id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_views";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_views (
	`topic_id` int(11) NOT NULL default '0',
	`forum_id` int(11) NOT NULL default '0',
	`user_id` mediumint(8) NOT NULL default '0',
	`time` int(11) default NULL
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_warn_log";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_warn_log (
	`wid` int(11) NOT NULL auto_increment,
	`mid` varchar(8) NOT NULL default '0',
	`author` varchar(40) NOT NULL,
	`action` char(1) NOT NULL,
	`cause` text NOT NULL,
	`date` int(10) NOT NULL default '0',
	PRIMARY KEY  (`wid`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "DROP TABLE IF EXISTS " . PREFIX . "_forum_moderators";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_moderators (
	`mid` mediumint(8) NOT NULL auto_increment,
	`forum_id` int(11) NOT NULL default '0',
	`member_name` varchar(32) NOT NULL default '',
	`member_id` mediumint(8) NOT NULL default '0',
	`edit_post` tinyint(1) default NULL,
	`edit_topic` tinyint(1) default NULL,
	`delete_post` tinyint(1) default NULL,
	`delete_topic` tinyint(1) default NULL,
	`open_topic` tinyint(1) default NULL,
	`close_topic` tinyint(1) default NULL,
	`mass_prune` tinyint(1) default NULL,
	`move_topic` tinyint(1) default NULL,
	`pin_topic` tinyint(1) default NULL,
	`unpin_topic` tinyint(1) default NULL,
	`allow_warn` tinyint(1) default NULL,
	`is_group` tinyint(1) default '0',
	`group_id` smallint(3) default NULL,
    `combining_post` tinyint(1) default NULL,
    `move_post` tinyint(1) default NULL,
    `banned` tinyint(1) default NULL,
    `read_mode` tinyint(1) default NULL,
	PRIMARY KEY  (`mid`),
	KEY `forum_id` (`forum_id`),
	KEY `group_id` (`group_id`),
	KEY `member_id` (`member_id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	// Mail tpl //
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (1, 'subscription_text', 'Здравствуйте, {%username_to%}!\r\n\r\n{%username_from%} ответил в тему \"{%topic_name%}\", на которую вы подписаны.\r\n\r\nТема находится по адресу:\r\n\r\n{%topic_link%}\r\n\r\n------------------------------------------------\r\nВы можете в любое время отписаться от такой рассылки через ссылку:\r\n\r\n{%topic_link_del%}\r\n\r\n------------------------------------------------\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (2, 'frend_text', '{%username_to%},\r\n\r\nДанное письмо вам отправил {%username_from%} с сайта $url\r\n\r\n------------------------------------------------\r\nТекст сообщения\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\n------------------------------------------------\r\nПомните, что администрация сайта не несет ответственности за содержание данного письма\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (3, 'report_text', 'Данную жалобу вам отправил {%username_from%} с сайта $url\r\n\r\n------------------------------------------------\r\nТекст жалобы\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\n------------------------------------------------\r\nКраткая информация о жалобе\r\n------------------------------------------------\r\n\r\nТема: {%topic_link%}\r\n\r\nID сообщения: {%post_id%}\r\n\r\n------------------------------------------------\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (4, 'new_topic', 'Уважаемый администратор,\r\n\r\nуведомляем вас о том, что на форум сайта  $url была добавлена тема.\r\n\r\n------------------------------------------------\r\nКраткая информация о теме\r\n------------------------------------------------\r\n\r\nАвтор: {%username%}\r\nДата добавления: {%date%}\r\nНазвание темы: {%title%}\r\nСсылка на тему: {%link%}\r\n\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
    //
    
    $db_query[] = "INSERT INTO " . PREFIX . "_forum_forums (`id`, `parentid`, `is_category`, `alt_name`, `topics`, `posts`, `name`, `description`, `position`, `status`, `access_read`, `access_write`, `access_mod`, `access_topic`, `access_upload`, `access_download`, `f_last_tid`, `f_last_title`, `f_last_date`, `f_last_poster_name`, `password`, `rules_title`, `rules`, `icon`, `moderators`, `postcount`, `fixpost`, `last_post_id`, `banner`, `q_reply`, `i_edit`, `redirect`) VALUES (1, -1, 1, '', 0, 0, 'Тестовая категория', '', 1, 1, '', '', '', '', '', '', 0, '', '0000-00-00 00:00:00', '', '', '', '', '', '', 1, 0, 0, '', 1, 0, '')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_forums (`id`, `parentid`, `is_category`, `alt_name`, `topics`, `posts`, `name`, `description`, `position`, `status`, `access_read`, `access_write`, `access_mod`, `access_topic`, `access_upload`, `access_download`, `f_last_tid`, `f_last_title`, `f_last_date`, `f_last_poster_name`, `password`, `rules_title`, `rules`, `icon`, `moderators`, `postcount`, `fixpost`, `last_post_id`, `banner`, `q_reply`, `i_edit`, `redirect`) VALUES (2, 1, 0, '', 1, 0, 'Тестовый форум', 'Тестовый форум может быть удален в любое время', 1, 1, '1:2:3:4:5', '1:2:3:4', '1:2', '1:2:3:4', '1:2:3', '1:2:3:4', 1, 'Добро пожаловать', '{$topic_date}', 'ShVad', '', '', '', '', '', 1, 0, 0, '', 1, 1, '')";
    
    $db_query[] = "INSERT INTO " . PREFIX . "_forum_topics (`tid`, `forum_id`, `alt_name`, `title`, `topic_descr`, `icon`, `post`, `views`, `author_topic`, `start_date`, `last_date`, `last_poster_name`, `topic_status`, `hidden`, `fixed`, `poll_title`, `frage`, `poll_body`, `poll_count`, `answer`, `multiple`, `meta_descr`, `meta_keywords`, `first_post`, `last_post_id`) VALUES (1, 2, '', 'Добро пожаловать', '', 'icon13', 0, 1, 'Admin', '{$topic_date}', '{$topic_date}', 'Admin', 0, 0, 1, '', '', '', 0, '', 0, 'Добро пожаловать : Добро пожаловать в ваш новый форум - DLE Forum!  С вопросами и предложениями обращайтесь на форум поддержки.  На нашем сайте Вы можете заказать годовую лицензию на форум. ', 'форум, Добро, можете, пожаловать, разрешение, снятие, копирайтов, также, течении, дополнительная, поддержка, Созданные, одного, раздел, время, уважением, Files, любое, удалить, форума', 1, 1)";
	
	$db_query[] = "INSERT INTO `dle_forum_posts` (`pid`, `topic_id`, `post_date`, `post_author`, `post_text`, `post_ip`, `is_register`, `e_mail`, `edit_user`, `edit_time`, `hidden`, `wysiwyg`, `is_count`) VALUES (1, 1, '{$topic_date}', 'Admin', 'Добро пожаловать в ваш новый форум - <a href=\"http://dlekey.cn/products/1-dle-forum.html\" target=\"_blank\">DLE Forum</a>!<br /><br />С вопросами и предложениями обращайтесь на <a href=\"http://dlekey.cn/forum/category_2\" target=\"_blank\">форум поддержки</a>.<br /><br />На нашем сайте Вы можете <a href=\"http://dlekey.cn/clientarea/buy/product-1.html\" target=\"_blank\">заказать годовую лицензию на форум</a>.<br /><br />Стоимость лицензии составляет: <!--colorstart:#FF0000--><span style=\"color:#FF0000\"><!--/colorstart-->30 USD<!--colorend--></span><!--/colorend-->.<br />В данную стоимость входит разовая установка форума, дополнительная тех. поддержка в течении одного года, а также <u>разрешение на снятие копирайтов</u>.<br /><br />Созданные раздел, форум и это сообщение - тестовые, вы можете удалить их в любое время.<br /><br /><b>С уважением,<br /><br /><a href=\"http://dlekey.cn\" target=\"_blank\">DLE Files Group</a></b>', '127.0.0.1', 1, '', '0', 0, 0, 0, 1)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (1, 'red', 1, 1, 1, 1, 1, 1, 1, '0', 0, 0, 1, 1)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (2, 'blue', 0, 1, 1, 1, 1, 1, 1, '0', 0, 0, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (3, '', 0, 1, 1, 1, 0, 0, 1, '1', 0, 1, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (4, '', 0, 1, 1, 0, 0, 0, 1, '1', 0, 1, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_groups VALUES (5, '', 0, 0, 0, 0, 0, 0, 0, '0', 0, 0, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (1, 0, '', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (2, 0, '', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 2, 0, 0, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (3, 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 3, 0, 0, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (4, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_moderators VALUES (5, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0)";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_titles VALUES (1, 0, 'Новичок', '1')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_titles VALUES (2, 10, 'Участник', '2')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_titles VALUES (3, 30, 'Активный участник', '3')";
	
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_post`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_warn`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_update`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_rank`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_pips`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_reputation`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_last`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_time`";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_users DROP `forum_read`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_post DROP `news_tid`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_category DROP `forum_id`";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_post` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_warn` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_update` VARCHAR( 20 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_rank` VARCHAR( 40 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_pips` SMALLINT( 2 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_reputation` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_last` VARCHAR( 20 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_time` VARCHAR( 20 ) DEFAULT '0' NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_read` VARCHAR( 20 ) NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_post ADD `news_tid` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_category ADD `forum_id` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
?>