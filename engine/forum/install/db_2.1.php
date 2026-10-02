<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db_2.1.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_rank` VARCHAR( 40 ) NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_category ADD `forum_id` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `icon` VARCHAR( 40 ) NOT NULL";
	
	$db_query[] = "ALTER TABLE " . PREFIX . "_post ADD `news_tid` SMALLINT( 5 ) DEFAULT '0' NOT NULL";
	
	$db_query[] = "CREATE TABLE " . PREFIX . "_forum_email (
	`id` tinyint(3) NOT NULL default '0',
	`name` varchar(40) NOT NULL default '',
	`template` text NOT NULL,
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM /*!40101 DEFAULT CHARACTER SET {$db_charset} COLLATE {$db_collate} */";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (1, 'subscription_text', 'Здравствуйте, {%username_to%}!\r\n\r\n{%username_from%} ответил в тему \"{%topic_name%}\", на которую вы подписаны.\r\n\r\nТема находится по адресу:\r\n\r\n{%topic_link%}\r\n\r\n------------------------------------------------\r\nВы можете в любое время отписаться от такой рассылки через ссылку:\r\n\r\n{%topic_link_del%}\r\n\r\n------------------------------------------------\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (2, 'frend_text', '{%username_to%},\r\n\r\nДанное письмо вам отправил {%username_from%} с сайта $url\r\n\r\n------------------------------------------------\r\nТекст сообщения\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\n------------------------------------------------\r\nПомните, что администрация сайта не несет ответственности за содержание данного письма\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (3, 'report_text', 'Данную жалобу вам отправил {%username_from%} с сайта $url\r\n\r\n------------------------------------------------\r\nТекст жалобы\r\n------------------------------------------------\r\n\r\n{%text%}\r\n\r\n------------------------------------------------\r\nКраткая информация о жалобе\r\n------------------------------------------------\r\n\r\nТема: {%topic_link%}\r\n\r\nID сообщения: {%post_id%}\r\n\r\n------------------------------------------------\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
	$db_query[] = "INSERT INTO " . PREFIX . "_forum_email VALUES (4, 'new_topic', 'Уважаемый администратор,\r\n\r\nуведомляем вас о том, что на форум сайта  $url была добавлена тема.\r\n\r\n------------------------------------------------\r\nКраткая информация о теме\r\n------------------------------------------------\r\n\r\nАвтор: {%username%}\r\nДата добавления: {%date%}\r\nНазвание темы: {%title%}\r\nСсылка на тему: {%link%}\r\n\r\n\r\nС уважением,\r\n\r\nАдминистрация $url')";
	
?>