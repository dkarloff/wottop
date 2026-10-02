<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: write.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}
	
	$edit_files = array
	(
		array
		(
			'open' => './engine/engine.php',
			'key'  => '/forum/main.php',
			'find' => 'switch ( $do ) {',
			'add'  => 'case "forum" :
			include ENGINE_DIR.\'/forum/main.php\';
			break;',
		),
		
		array
		(
			'open' => './engine/inc/options.php',
			'key'  => 'forum.php',
			'find' => '$options[\'others\'] = array (',
			'add'  => 'array(
			\'name\'       => "DLE Forum",
			\'url\'        => "$PHP_SELF?mod=forum",
			\'descr\'      => "Панель управления форумом.",
			\'image\'      => "dle_forum.png",
			\'access\'     => "admin",
			),',
		),
		
		array
		(
			'open' => './.htaccess',
			'key'  => '# DLE Forum',
			'find' => 'RewriteEngine On',
			'add'  => '# DLE Forum
RewriteRule ^forum(/?)+$ /index.php?do=forum [L]
RewriteRule ^forum/category_([0-9]+)(/?)+$ index.php?do=forum&category=$1 [L]
RewriteRule ^forum/forum_([0-9]+)(/?)+$ index.php?do=forum&showforum=$1 [L]
RewriteRule ^forum/forum_([0-9]+)/([0-9]+)(/?)+$ index.php?do=forum&showforum=$1&cstart=$2 [L]
RewriteRule ^forum/topic_([0-9]+)(/?)+$ index.php?do=forum&showtopic=$1 [L]
RewriteRule ^forum/topic_([0-9]+)/last(/?)+$ index.php?do=forum&showtopic=$1&lastpost=1 [L]
RewriteRule ^forum/topic_([0-9]+)/([0-9]+)(/?)+$ index.php?do=forum&showtopic=$1&cstart=$2 [L]
RewriteRule ^forum/topic_([0-9]+)/reply(/?)+$ index.php?do=forum&act=_topic&code=reply&tid=$1 [L]
RewriteRule ^forum/forum_([0-9]+)/add(/?)+$ index.php?do=forum&act=add_topic&forum_id=$1 [L]
RewriteRule ^forum/search(/?)+$ index.php?do=forum&act=search [L]
RewriteRule ^forum/search/([0-9]+)-([^/]+)/([0-9]+)(/?)+$ index.php?do=forum&act=search&count_all=$1&search_text=$2&cstart=$3 [L]
RewriteRule ^forum/subscription(/?)+$ index.php?do=forum&act=subscription [L]
RewriteRule ^forum/getnew(/?)+$ index.php?do=forum&act=getnew [L]
RewriteRule ^forum/getnew/([0-9]+)(/?)+$ index.php?do=forum&act=getnew&cstart=$1 [L]
RewriteRule ^forum/warn/([^/]*)(/?)+$ index.php?do=forum&act=warn&user=$1 [L]
RewriteRule ^forum/warn/([^/]*)/([0-9]+)(/?)+$ index.php?do=forum&act=warn&user=$1&cstart=$2 [L]
RewriteRule ^forum/reputation/([^/]*)(/?)+$ index.php?do=forum&act=reputation&user=$1 [L]
RewriteRule ^forum/reputation/([^/]*)/([0-9]+)(/?)+$ index.php?do=forum&act=reputation&user=$1&cstart=$2 [L]
RewriteRule ^forum/textversion.html$ engine/forum/textversion.php [L]',
		),
	);
	
?>