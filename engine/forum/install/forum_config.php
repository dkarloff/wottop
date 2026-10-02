<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: forum_config.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

$forum_config = <<<HTML
<?PHP 

//System Configurations

\$forum_config = array (

'forum_title' => "DLE Forum 2.6",

'forum_url' => "",

'meta_descr' => "",

'meta_keywords' => "",

'meta_topic' => "1",

'sep_subforum' => ",&amp;nbsp;",

'sep_moderators' => ",&amp;nbsp;",

'last_abc' => "20",

'mod_rewrite' => "1",

'wysiwyg' => "0",

'offline' => "0",

'timestamp' => "j F Y H:i",

'sessions_log' => "1",

'session_time' => "15",

'stats' => "1",

'online' => "1",

'forum_bar' => "1",

'topic_inpage' => "25",

'topic_hot' => "30",

'post_inpage' => "20",

'post_hide' => "10",

'topic_abc' => "0",

'post_maxlen' => "10000",

'auto_wrap' => "80",

'post_update' => "1",

'last_plink' => "1",

'hide_forum' => "0",

'topic_sort' => "1",

'topic_email' => "1",

'forum_pr_imp' => "Важно:",

'forum_pr_vote' => "Опрос:",

'forum_pr_modr' => "Модерация:",

'forum_pr_sub' => "Подфорумы:",

'mod_report' => "0",

'flood_time' => "15",

'warn' => "1",

'warn_max' => "5",

'warn_day' => "3",

'warn_show' => "1",

'warn_show_all' => "0",

'warn_sh_pg' => "0",

'subscription' => "1",

'mod_icq' => "1",

'mod_rank' => "1",

'reputation' => "1",

'ses_forum' => "1",

'ses_topic' => "1",

'bot_agent' => "1",

'discuss' => "1",

'discuss_title' => "1",

'discuss_title_tpl' => "Статья: {post_title}",

'tools_disc_post' => "1",

'discuss_post_tpl' => "Здесь обсуждается статья: [url={post_link}]{post_title}[/url]",

'set_topic_post' => "1",

'set_post_num_up' => "0",

'set_post_num_day' => "1",

'topic_new_day' => "5",

'set_sub_last_up' => "1",

'upload_type' => "zip,rar,exe,doc,pdf",

'img_upload' => "1",

'img_size' => "1024",

'thumb_size' => "150",

'jpeg_quality' => "85",

'tag_img_width' => "0",

'warn_group' => "1",

'search_captcha' => "5",

'topic_captcha' => "5",

'post_captcha' => "5",

'tools_upload' => "1",

'tools_poll' => "1:2",

'warn_show_group' => "1:2:3",

'rep_edit_group' => "1",

'version_id' => "2.6",

);

?>
HTML;
	
?>