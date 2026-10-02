<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: stats.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
	$act = $_REQUEST['act'];

	if ($forum_config['stats'] AND !$act)
	{		
		$stats_array = array();

		$today_date = date('d');
		
		$stats_array = $fcache->get('stats');
		
		if (!$stats_array OR $stats_array['date'] != $today_date)
		{
			$stats_array = FALSE;
			
			$stats_array = array();
			
			$row_post = $db->super_query("SELECT COUNT(pid) as count FROM " . PREFIX . "_forum_posts");
			
			$stats_array['posts'] = $row_post['count'];
			
			$row_topic = $db->super_query("SELECT COUNT(tid) as count FROM " . PREFIX . "_forum_topics");
			
			$stats_array['topics'] = $row_topic['count'];
			
			$row_users_reg = $db->super_query("SELECT COUNT(user_id) as count FROM " . PREFIX . "_users");
			
			$stats_array['users_reg'] = $row_users_reg['count'];
			
			$row_users = $db->super_query("SELECT COUNT(user_id) as count FROM " . PREFIX . "_users  WHERE forum_post");
			
			$stats_array['users'] = $row_users['count'];
			
			$result_users = $db->query("SELECT * FROM " . PREFIX . "_users WHERE forum_post ORDER BY forum_post DESC LIMIT 10");
			
			while ($row = $db->get_row($result_users))
			{
				$top_count++;
				
				if ($top_count > 1)
				{
					$symbol = ", ";
				}
				
				$group_span = $forum_groups[$row['user_group']]['group_colour'];
				
				if ($forum_config['mod_rewrite'])
				{
					$a_top_users = "{$config['http_home_url']}user/".urlencode($row['name'])."/";
				} else 
				{
					$a_top_users = "{$config['http_home_url']}?subaction=userinfo&user=".urlencode($row['name'])."";
				}
				$stats_array['top_users'] .= $symbol."<a href=\"{$a_top_users}\"><span style=\"color:{$group_span}\">$row[name]</span></a>";
			}
			
			if (!$stats_array['top_users'])
			{
				$stats_array['top_users'] = "--";
			}
			
			$stats_array['date'] = $today_date;
			
			$last_user_row = $db->super_query("SELECT * FROM " . PREFIX . "_users ORDER BY user_id DESC LIMIT 1");
			
			$stats_array['last_user'] = link_user($last_user_row['name']);
			
			$stats_array['max_online']      = $max_online['count'];
			$stats_array['max_online_time'] = langdate('j F Y, H:i', $max_online['time']);
			
			$fcache->set('stats', $stats_array);
		}
		
		// tpl compile //
		$tpl->load_template($tpl_dir.'stats.tpl');
		
		if ($forum_config['online'] and $forum_config['sessions_log'])
		{
			get_forum_online();
			
			$tpl->set('[online-stats]','');
			$tpl->set('[/online-stats]','');
		}
		else
		{
			$tpl->set_block("'\\[online-stats\\](.*?)\\[/online-stats\\]'si","");
		}
		
		$tpl->set('{post-num}', $stats_array['posts']);
		$tpl->set('{topic-num}', $stats_array['topics']);
		$tpl->set('{all-users}', $stats_array['users_reg']);
		$tpl->set('{forum-users}', $stats_array['users']);
		$tpl->set('{top-users}', $stats_array['top_users']);
		
		$tpl->set('{online-num}', $forum_online['all_count']);
		$tpl->set('{member_count}', $forum_online['member_count']);
		$tpl->set('{guest_count}', $forum_online['guest_count']);
		$tpl->set('{online_time}', $forum_config['session_time']);
		
		if (!$forum_online['member_list'])
		{
			$forum_online['member_list'] = ""; // default value: --
		}
		
		$tpl->set('{member_online}', $forum_online['member_list']);
		
		$tpl->set('{last_user}', $stats_array['last_user']);
		$tpl->set('{max_online}', $stats_array['max_online']);
		$tpl->set('{max_online_time}', $stats_array['max_online_time']);
		
		$tpl->compile('forum_stats');
		$tpl->clear();
	}
?>