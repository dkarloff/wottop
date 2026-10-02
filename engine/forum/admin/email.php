<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: email.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

	$subaction = $_GET['subaction'];
    
    if ($subaction == "save")
	{
		$find = array("'<'", "'>'",);
					 
		$replace = array("&lt;", "&gt;",);
		
		$subscription_text = preg_replace($find,$replace, $db->safesql($_POST['subscription_text']));
		
		$db->query("UPDATE " . PREFIX . "_forum_email set template='$subscription_text' where name='subscription_text'");
		
		$frend_text = preg_replace($find,$replace, $db->safesql($_POST['frend_text']));
		
		$db->query("UPDATE " . PREFIX . "_forum_email set template='$frend_text' where name='frend_text'");
		
		
		$report_text = preg_replace($find,$replace, $db->safesql($_POST['report_text']));
		
		$db->query("UPDATE " . PREFIX . "_forum_email set template='$report_text' where name='report_text'");
		
		$new_topic = preg_replace($find,$replace, $db->safesql($_POST['new_topic']));
		
		$db->query("UPDATE " . PREFIX . "_forum_email set template='$new_topic' where name='new_topic'");
		
		
		msg("info",$f_lg['email_ok'], $f_lg['email_ok2'], "?mod=forum");
	}
	
	else
	{
		$db->query("SELECT name, template FROM " . PREFIX . "_forum_email");
		
		while($row = $db->get_row())
		{
			$$row['name'] = stripslashes($row['template']);
		}
		
		$db->free();
		
		echo_top();
		
		echo "<form action=\"$PHP_SELF?mod=forum&action=email&subaction=save\" method=\"post\">";
		
		echo_title($f_lg['mail_subscr']);
		
		echo_mail("subscription_text", $subscription_text, $f_lg['mail_subscr_']);
		
		echo_title($f_lg['mail_new_topic']);
		
		echo_mail("new_topic", $new_topic, $f_lg['mail_new_topic_']);
		
		echo_title($f_lg['mail_frend']);
		
		echo_mail("frend_text", $frend_text, $f_lg['mail_frend_']);
		
		echo_title($f_lg['mail_report']);
		
		echo_mail("report_text", $report_text, $f_lg['mail_report_']);
		
		echo "<br /><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_save']}\"></form>";
		
		echo_bottom();
	}
	
?>