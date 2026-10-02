<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: service.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
	$subaction = $_GET['subaction'];
	
	switch ($subaction)
	{
		case "":
		
		echo_top();
		
		echo_top('1');
		
		echo_title('Очистка журнала предупреждений');
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=service&subaction=warn\">
		<table border=\"0\" width=\"100%\">
		<tr>
		<td width=\"260\">{$f_lg['titles_uname']}</td>
		<td><input class=\"edit\" type=\"text\" name=\"user_name\" value=\"\" size=\"27\"> {$f_lg['svce_full']}</td></tr>
		<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
		<tr>
		<td width=\"260\">&nbsp;</td>
		<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_start']}\"></td></tr>
		</table></form>";
		
		echo_bottom('1');
		
		echo_top('1');
		
		echo_title('Очистка журнала подписок на темы');
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=service&subaction=subscription\">
		<table border=\"0\" width=\"100%\">
		<tr>
		<td width=\"260\">{$f_lg['titles_uname']}</td>
		<td><input class=\"edit\" type=\"text\" name=\"user_name\" value=\"\" size=\"27\"> {$f_lg['svce_full']}</td></tr>
		<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
		<tr>
		<td width=\"260\">&nbsp;</td>
		<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_start']}\"></td></tr>
		</table></form>";
		
		echo_bottom('1');
		
		echo_top('1');
		
		echo_title('Очистка журнала репутации');
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=service&subaction=reputation\">
		<table border=\"0\" width=\"100%\">
		<tr>
		<td width=\"260\">{$f_lg['titles_uname']}</td>
		<td><input class=\"edit\" type=\"text\" name=\"user_name\" value=\"\" size=\"27\"> {$f_lg['svce_full']}</td></tr>
		<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
		<tr>
		<td width=\"260\">&nbsp;</td>
		<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_start']}\"></td></tr>
		</table></form>";
		
		echo_bottom('1');
		
		echo_top('1');
		
		echo_title('Очистка журнала просмотра тем');
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=service&subaction=views\">
		Будет очищен журнал просмотра тем. Все темы до данного времени будут иметь статус прочитанных.
		<table border=\"0\" width=\"100%\">
		<tr>
		<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
		<tr>
		<td width=\"260\">&nbsp;</td>
		<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_start']}\"></td></tr>
		</table></form>";
		
		echo_bottom('1');
		
		echo_top('1');
		
		echo_title('Очистка журнала голосований');
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=service&subaction=poll\">
		При очистке журнала, пользователи смогут повторно голосовать.
		<table border=\"0\" width=\"100%\">
		<tr>
		<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
		<tr>
		<td width=\"260\">&nbsp;</td>
		<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_start']}\"></td></tr>
		</table></form>";
		
		echo_bottom('1');
		
		echo_bottom();
		
		break;

// ********************************************************************************
// WARN LOG
// ********************************************************************************
		case "warn":
		
		$user_name = $db->safesql($_REQUEST['user_name']);
		
		if ($user_name)
		{
			$row = $db->super_query("SELECT user_id FROM " . PREFIX . "_users WHERE name = '{$user_name}'");
		}
		
		if ($row['user_id'])
		{
			$db->query("DELETE FROM " . PREFIX . "_forum_warn_log WHERE mid = '{$row['user_id']}'");
		}
		
		if (!$user_name)
		{
			$db->query("TRUNCATE TABLE " . PREFIX . "_forum_warn_log");
		}
		
		header("Location: ?mod=forum&action=service");
		
		break;
		
// ********************************************************************************
// SUBSCRIPTION
// ********************************************************************************
		case "subscription":
		
		$user_name = $db->safesql($_REQUEST['user_name']);
		
		if ($user_name)
		{
			$row = $db->super_query("SELECT user_id FROM " . PREFIX . "_users WHERE name = '{$user_name}'");
		}
		
		if ($row['user_id'])
		{
			$db->query("DELETE FROM " . PREFIX . "_forum_subscription WHERE user_id = '{$row['user_id']}'");
		}
		
		if (!$user_name)
		{
			$db->query("TRUNCATE TABLE " . PREFIX . "_forum_subscription");
		}
		
		header("Location: ?mod=forum&action=service");
		
		break;

// ********************************************************************************
// REPUTATION LOG
// ********************************************************************************
		case "reputation":
		
		$user_name = $db->safesql($_REQUEST['user_name']);
		
		if ($user_name)
		{
			$row = $db->super_query("SELECT user_id FROM " . PREFIX . "_users WHERE name = '{$user_name}'");
		}
		
		if ($row['user_id'])
		{
			$db->query("DELETE FROM " . PREFIX . "_forum_reputation_log WHERE mid = '{$row['user_id']}'");
		}
		
		if (!$user_name)
		{
			$db->query("TRUNCATE TABLE " . PREFIX . "_forum_reputation_log");
		}
		
		header("Location: ?mod=forum&action=service");
		
		break;

// ********************************************************************************
// VIEWS LOG
// ********************************************************************************
		case "views":
		
		$db->query("TRUNCATE TABLE " . PREFIX . "_forum_views");
		
		$db->query("UPDATE " . PREFIX . "_users SET forum_last = '".time()."', forum_time = '".time()."'");
		
		$_SESSION['member_lasttime'] = time();
		
		header("Location: ?mod=forum&action=service");
		
		break;

// ********************************************************************************
// POLL LOG
// ********************************************************************************
		case "poll":
		
		$db->query("TRUNCATE TABLE " . PREFIX . "_forum_poll_log");
		
		header("Location: ?mod=forum&action=service");
		
		break;
		
	}
	
?>