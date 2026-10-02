<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: search.php
=====================================================
 Copyright (c) 2008,2010 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
	if ($forum_config['mod_rewrite'] && $_GET['search_text'])
    {
        $_search_text = explode('-', $_GET['search_text']);
        
        $_GET['search_text'] = $_search_text[1];
    }
    
    if (isset($_POST['search_text']))
	{
		$search_text = substr ( strip_data( $_REQUEST['search_text'] ), 0, 90 );
		
		$search_text_explode = explode (' ', $search_text);
		
		if (count($search_text_explode))
		{
			foreach ($search_text_explode as $key => $value)
			{
				$value = trim ($value);
				
				if ($value !== "" and strlen($value) > 3)
				{
					$search_text_array[] = $value;
				}
			}
			
			if (count($search_text_array))
			{
				$search_list = implode('|', $search_text_array);
			}
		}
        
        $_SESSION['search_q']                             = md5($_POST['search_text']);
        $_SESSION['search_' . md5($_POST['search_text'])] = $search_list;
        $_GET['search_text']                              = md5($_POST['search_text']);
        $_SESSION['search_in']                            = $_REQUEST['search_in'];
	}
	
    $search_list = $_SESSION['search_' . $_GET['search_text']];
    
	$search_fid = $_REQUEST['search_fid'];
	
	$count_all = intval($_REQUEST['count_all']);
	
	if ($forum_config['search_captcha'])
	{
		$search_captcha = check_access($forum_config['search_captcha']);
	}
	
	if ($search_captcha)
	{
		if ($_REQUEST['sec_code'])
		{
			$_SESSION['captcha_search'] = $_REQUEST['sec_code'];
		}
		
		if ($_SESSION['captcha_search'] == $_SESSION['sec_code_session'])
		{
			$search_captcha_true = TRUE;
		}
	}
	
	else
	{
		$search_captcha_true = TRUE;
	}
	
	if ($search_list AND $search_captcha_true)
	{
		if ($search_fid)
		{
			$_SESSION['search_fid'] = $search_fid;
		}
		
		if ($is_logged)
		{
			$row_views = $db->query("SELECT topic_id FROM " . PREFIX . "_forum_views WHERE user_id = '$member_id[user_id]'");
			
			$topic_views = array();
			
			while ($row = $db->get_row($row_views))
			{
				$topic_views[$row['topic_id']] = '1';
			}
		}
		else
		{
			$row_views = explode(",", $_COOKIE['dle_forum_views']);
			
			foreach ($row_views as $value)
			{
				$topic_views[$value] = '1';
			}
		}
		
		if ($cstart){
		$cstart = $cstart - 1;
		$cstart = $cstart * $forum_config['topic_inpage'];
		}
		
		$config_inpage = $forum_config['topic_inpage'];
		
		if ($search_fid)
		{
			if (is_array($search_fid))
			{
				$search_fid_array = array();
                
                foreach ($search_fid as $key => $value)
                {
                    $search_fid_array[$key] = intval($value);
                }
                
                $fid_list = implode(',', $search_fid_array);
			}
			
			else
			{
				$fid_list = intval($search_fid);
			}
		}
		
		$fid_q = $fid_list ? "and forum_id IN ({$fid_list}) " : "";
		
        $access_read = $dle_forum->access_read_list();
        $access_read = implode(',', $access_read);
        
        if (!$access_read) { $access_read = 0; }
        
		if ($_REQUEST['search_in'] == "titles" OR $_SESSION['search_in'] == "titles")
		{
			$mysql_query = "SELECT * FROM " . PREFIX . "_forum_topics
			                         WHERE title REGEXP ('$search_list') {$fid_q}and hidden = 0 and forum_id IN ({$access_read})
									 OR topic_descr REGEXP ('$search_list') {$fid_q}and hidden = 0 and forum_id IN ({$access_read})";
									 
		    $count_query = "SELECT COUNT(tid) as count FROM " . PREFIX . "_forum_topics
			                         WHERE title REGEXP ('$search_list') {$fid_q}and hidden = 0 and forum_id IN ({$access_read})
									 OR topic_descr REGEXP ('$search_list') {$fid_q}and hidden = 0 and forum_id IN ({$access_read})";
		}
		
		else
		{
			$mysql_query = "SELECT p.*, t.* FROM " . PREFIX . "_forum_posts AS p LEFT JOIN " . PREFIX . "_forum_topics AS t ON t.tid = p.topic_id
											WHERE p.post_text REGEXP ('$search_list') {$fid_q}and p.hidden = 0 and t.forum_id IN ({$access_read}) GROUP BY t.tid";
											
			$count_query = "SELECT SQL_CALC_FOUND_ROWS * FROM " . PREFIX . "_forum_posts AS p LEFT JOIN " . PREFIX . "_forum_topics AS t
			                                ON t.tid = p.topic_id
											WHERE p.post_text REGEXP ('$search_list') {$fid_q}and p.hidden = 0 and t.forum_id IN ({$access_read}) GROUP BY t.tid";
		}
		
		$result_topics = $db->query("" . $mysql_query . " LIMIT ".$cstart.",".$forum_config['topic_inpage']."");
		
		if (!$count_all)
		{
			if ($_REQUEST['search_in'] == "titles")
			{
				$count_get = $db->super_query ($count_query);
				
				$count_all = $count_get['count'];
			}
			else
			{
				$count_all = $db->num_rows($db->query($count_query));
			}
		}

		if ($count_all){
		
		if ($forum_config['mod_rewrite'])
		{
			$icat = $forum_url . "/search/" . $count_all . "-" . $_SESSION['search_q'] ."/";
		}
		else
		{
			$icat = $forum_url . "act=search&count_all=" . $count_all . "&search_text=" . $_SESSION['search_q'] . "&cstart=";
		}
		
		require_once ENGINE_DIR.'/forum/sources/showtopics.php';
		
		require_once ENGINE_DIR.'/forum/sources/components/navigation.php';
		
		$tpl->load_template($tpl_dir.'forum.tpl');
		
		$tpl->set('{forum}', $f_lang['search_result']);
		
		$tpl->set('{subforums}', '');
        
        $tpl->set('{banner}', '');
		
		$tpl->set('{topics}', $tpl->result['topics']);
		
		$tpl->set('{info}', $msg_info);
		
		$tpl->set('{navigation}', $tpl->result['navigation']);
		
		$tpl->set_block("'\\[options\\](.*?)\\[/options\\]'si", '');
		
		$tpl->set_block("'\\[rules\\](.*?)\\[/rules\\]'si","");
		
		$tpl->set_block("'\\[new_topic\\](.*?)\\[/new_topic\\]'si","");
		
		$tpl->set_block("'\\[selected\\](.*?)\\[/selected\\]'si","");
		
		$tpl->set_block("'\\[fast-search\\](.*?)\\[/fast-search\\]'si","");
		
		$tpl->set_block("'\\[moderation\\](.*?)\\[/moderation\\]'si","");
		
		$tpl->set_block("'\\[online\\](.*?)\\[/online\\]'si","");
		
		$tpl->compile('dle_forum');
		
		$tpl->clear();
		
		}
		
		else
		{
			forum_msg($f_lang['f_msg'], $f_lang['search_nresult']);
		}
	}
	
	elseif ($_REQUEST['search_text'] and $search_maxlen)
	{
		forum_msg($f_lang['f_msg'], $f_lang['search_error']);
	}
	
	else
	{
		$_SESSION['search_fid'] = "";
		
		$tpl->load_template($tpl_dir.'search.tpl');
		
		$tpl->copy_template = "<form  method=\"post\" action=\"\">".$tpl->copy_template."</form>";
		
		if ($search_captcha)
		{
			$tpl->set('[sec_code]',"");
			$tpl->set('[/sec_code]',"");
			
			$path = parse_url($config['http_home_url']);
			$anti_bot = !defined('FORUM_SUB_DOMAIN') ? 'engine/modules/' : '';
			
			$tpl->set('{sec_code}',"<span id=\"dle-captcha\"><img src=\"".$path['path'].$anti_bot."antibot.php\" alt=\"${lang['sec_image']}\" border=\"0\"></span>");
		}
		
		else
		{
			$tpl->set('{sec_code}',"");
			$tpl->set_block("'\\[sec_code\\](.*?)\\[/sec_code\\]'si","");
		}
		
		$select_list = "<select name='search_fid[]' size='10' multiple='multiple'>";
		
		$select_list .= "<option value='0' selected='selected'>&raquo; Все форумы</option>";
		
        $select_list .= $dle_forum->forum_list(0, true);
		
		$select_list .= "</select>";
		
		$tpl->set('{forum_select}',$select_list);
		
		$tpl->compile('dle_forum');
		$tpl->clear();
	}
	
    $metatags['title'] = $forum_config['forum_title'] . ' &raquo; ' . $f_lang['app_search'];
    
	$forum_bar_array[] = $f_lang['app_search'];
	
?>