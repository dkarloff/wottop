<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: show.last.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

@include ENGINE_DIR.'/data/forum_config.php';

$forum_config['site_inpage'] = '10';

if ($forum_config['site_inpage'] and $do !== "forum")
{
	//$forum_table = dle_cache ('dlef_show_last_' . $member_id['user_group']);
	
    $forum_table = '';
    
	if (!$forum_table)
	{
		$access_hide = "WHERE ". PREFIX ."_forum_forums.access_read regexp '[[:<:]](".$member_id['user_group'].")[[:>:]]'";
		
		$result = $db->query("SELECT * FROM " . PREFIX . "_forum_topics LEFT JOIN ". PREFIX ."_forum_forums ON ". PREFIX ."_forum_topics.forum_id = ". PREFIX ."_forum_forums.id {$access_hide} GROUP BY last_date DESC LIMIT ".$forum_config['site_inpage']."");
		
		while ($row = $db->get_row ($result))
		{
			$author_topic = urlencode ($row['author_topic']);
			
			$last_poster_name = urlencode ($row['last_poster_name']);
			
            $row['name']  = stripcslashes($row['name']);
            $row['title'] = stripcslashes($row['title']);
            
			if ($forum_config['mod_rewrite'])
			{
				$fl_forum = "<a href='/forum/forum_{$row['id']}'>{$row['name']}</a>";
				
				$fl_topic = "<a href='/forum/topic_$row[tid]/last#reply'>{$row['title']}</a>";
				
$fl_author = "<a onclick=\"ShowProfile('$author_topic', '". $config['http_home_url']."user/$author_topic/'); return false;\" href='{$config['http_home_url']}user/$author_topic'>{$row['author_topic']}</a>";
                $fl_last_poster = "<a onclick=\"ShowProfile('$last_poster_name', '". $config['http_home_url']."user/$last_poster_name/'); return false;\" href='{$config['http_home_url']}user/$last_poster_name/'>{$row['last_poster_name']}</a>";
			}
			else
			{
				$fl_forum = "<a href='/?do=forum&showforum={$row['id']}'>{$row['name']}</a>";
				
				$fl_topic = "<a href='/?do=forum&showtopic=$row[tid]&lastpost=1'>{$row['title']}</a>";
				
$fl_author = "<a onclick=\"ShowProfile('$author_topic', '". $config['http_home_url']."?subaction=userinfo&user=$author_topic/'); return false;\" href='{$config['http_home_url']}?subaction=userinfo&user=$author_topic'>{$row['author_topic']}</a>";
$fl_last_poster = "<a onclick=\"ShowProfile('$last_poster_name', '". $config['http_home_url']."?subaction=userinfo&user=$last_poster_name/'); return false;\" href='{$config['http_home_url']}?subaction=userinfo&user=$last_poster_name'>{$row['last_poster_name']}</a>";
			}
			
			$row['last_date'] = strtotime($row['last_date']);
			
			if (date(Ymd, $row['last_date']) == date(Ymd, $_TIME))
			{
				$show_date = $lang['time_heute'].langdate(", H:i", $row['last_date']);
			}
			elseif (date(Ymd, $row['last_date']) == date(Ymd, ($_TIME - 86400)))
			{
				$show_date = $lang['time_gestern'].langdate(", H:i", $row['last_date']);
			}
			else
			{
				$show_date = langdate($forum_config['timestamp'], $row['last_date']);
			}
			
			$tpl->load_template('forum_last_list.tpl');
			
			$tpl->set('{fl_forum}', $fl_forum);
			
			$tpl->set('{fl_topic}', $fl_topic);
			
			$tpl->set('{fl_post}', $row['post']);
			
			$tpl->set('{fl_views}', $row['views']);
			
			$tpl->set('{fl_author}', $fl_author);
			
			$tpl->set('{fl_last_date}', $show_date);
			
			$tpl->set('{fl_last_poster}', $fl_last_poster);
			
			$tpl->compile('forum_last_list');
			$tpl->clear();
		}
		
		$tpl->load_template('forum_last.tpl');
		
		$tpl->set('{last_list}', $tpl->result["forum_last_list"]);
		
		$tpl->compile('forum_table');
		$tpl->clear();
		
		//create_cache ('dlef_show_last_' . $member_id['user_group'], $tpl->result['forum_table']);
	}
	
	else
	{
		$tpl->result['forum_table'] = $forum_table;
	}
}

?>