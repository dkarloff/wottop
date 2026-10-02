<?php if(!defined('DATALIFEENGINE')) { die(); }
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: forum.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

define ('DLE_FORUM_CP', true);

if($member_id['user_group'] != 1){ msg("error", "DLE Forum", $lang['db_denied']); }

$FVIDSN = '1302258644';

require_once ENGINE_DIR . '/forum/sources/components/include/cp.php';
    
    $dle_forum = new dle_forum_function;
    
    $dle_forum->Compile_CP();
    
	$action = $_REQUEST['action'];
	
	switch ($action)
	{
// ********************************************************************************
// MAIN
// ********************************************************************************
		case "":
		
		$count_options = count($options);
		
		for($i=0; $i<$count_options; $i++)
		{
			if($member_db[1] > $options[$i]['access'] AND $options[$i]['access'] != "all")
			{
				unset($options[$i]);
			}
		}
		
		$forum_stats = array();
		
		$row = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_forum_posts");
		$forum_stats['posts'] = $row['count'];
		
		$row = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_forum_topics");
		$forum_stats['topics'] = $row['count'];
		
		$forum_stats['licence'] = ($l_full) ? $f_lg['licence_trial'] : $f_lg['licence_full'];
		
		$sum_size = $db->super_query("SELECT SUM(file_size) AS sum FROM " . PREFIX . "_forum_files");
		
		$forum_stats['sum_size'] = formatsize($sum_size['sum']);
		
		if (!$forum_config['offline']) $forum_stats['line'] = $f_lg['forum_online'];
		else $forum_stats['line'] = $f_lg['forum_offline'];
		
		$forum_stats['cache'] = formatsize(dirsize("engine/forum/cache"));
		
		$forum_mysql = $db->query("SHOW TABLE STATUS FROM `".DBNAME."`");
		
		while ($row = $db->get_array($forum_mysql))
		{
			if (strpos($row['Name'], PREFIX."_forum_") !== false)
			
			$forum_db_size += $row['Data_length'] + $row['Index_length'] ;
		}
		
		$db->free($forum_mysql);
		
		$forum_stats['db_size'] = formatsize($forum_db_size);

		echo_top();
		echo_title($f_lg['m_forum']);
		
		echo "<table width=\"100%\">";
		
		foreach($options as $option)
		{
			if ($i > 1) {echo "</tr><tr>"; $i=0;}
			
			$i++;
			
			echo "<td width=\"50%\"><div class=\"quick\"><a href=\"{$option['url']}\"><img src=\"engine/forum/admin/ico/{$option['image']}\" border=\"0\" align=\"left\"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>";  
		}
		
		echo "</table>";
		
		echo_bottom(w);

		echo_updates_js();
		
		echo "<div id=\"update_box\" style=\"display:none\">";
		
		echo_top(w); echo_title($f_lg['check_updates']); echo "<div id=\"update_result\"></div>"; echo_bottom(w);
		
		echo "</div>";
		
		echo_top(w);
		
		echo_title($f_lg['m_stats']);
		
		echo_stats($forum_config, $forum_stats);
		
		echo_bottom();
		
		break;

// ********************************************************************************
// CATEGORY
// ********************************************************************************
		case "category":
		
		echo_top();
		
		echo_title($f_lg['cat_new']);
		
		echo_category('new');
		
		echo_bottom();
		
		break;

// ********************************************************************************
// CATEGORY EDIT
// ********************************************************************************
		case "category_edit":
		
        $id = intval($_GET['id']);
        
		$row = $db->super_query("SELECT * FROM " . PREFIX . "_forum_forums WHERE id = '$id'");
		
		$name = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"), $row['name']));
		
		$id = $row['id'];
		
		echo_top();
		
		echo_title($f_lg['cat_edit']);
		
		echo_category('edit', $name, $id);
		
		echo_bottom();
		
		break;

// ********************************************************************************
// CATEGORY ADD & SAVE
// ********************************************************************************
		case "category_save":
		
        $id   = intval($_GET['id']);
        
		$name = $db->safesql($_POST['name']);
		
		if ($name)
        {
            $dle_forum->cache->delete('categories');
            $dle_forum->cache->delete('forums_array');
            
            if ($id)
            {
                $db->query("UPDATE " . PREFIX . "_forum_forums SET name = '$name' WHERE id = '$id'");
                
                msg("info",$f_lg['cat_ok_edit1'], $f_lg['cat_ok_edit2'], "?mod=forum&action=content");
            }
            else
            {
                $result_posi = $db->super_query("SELECT position FROM " . PREFIX . "_forum_forums WHERE parentid = '-1' ORDER BY position DESC LIMIT 1");
                
                $posi = $result_posi['position'];
                
                if (!$posi) $posi = '1'; else $posi = ($posi+1);
                
                $db->query("INSERT INTO " . PREFIX . "_forum_forums (parentid, name, position, is_category) values ('-1', '$name', '$posi', '1')");
                
                msg("info",$f_lg['cat_ok_add1'], $f_lg['cat_ok_add2'], "?mod=forum&action=content");
            }
        }
        else
        {
            msg("error",$f_lg['error'],$f_lg['cat_err_name'], "javascript:history.go(-1)");
        }
        
		break;
					
// ********************************************************************************
// FORUM SORT
// ********************************************************************************
		case "forum_sort":
		
        $parent_id = intval($_REQUEST['parent_id']);
        
        $cat_posi  = $_POST['cat_posi'];
        
        if (!$parent_id)
        {
            $parent_id = '-1';
        }
        
		$result = $db->query("SELECT * FROM " . PREFIX . "_forum_forums WHERE parentid = '{$parent_id}'");
		
		while ($row = $db->get_row($result))
		{
			$db->query("UPDATE " . PREFIX . "_forum_forums SET position = '".intval($cat_posi[$row['id']])."' WHERE id = '{$row['id']}'");
		}
		
		$dle_forum->cache->delete('categories');
        $dle_forum->cache->delete('forums_array');
		//$dle_forum->cache->clear();
		
        if ($parent_id && $parent_id !== '-1')
        {
            header("Location: ?mod=forum&action=content_forum&id={$parent_id}");
        }
        else
        {
            header("Location: ?mod=forum&action=content");
        }
		
		break;
		
// ********************************************************************************
// FORUM
// ********************************************************************************
		case "forum":
		
        $parent_id = intval($_GET['parent_id']);
        
		echo_top();
		
		echo_title($f_lg['forum_new']);
		
		echo_forum('new', '', $parent_id);
		
		echo_bottom();
		
		break;
		
// ********************************************************************************
// FORUM EDIT
// ********************************************************************************
		case "forum_edit":
		
        $id = intval($_GET['id']);
        
		echo_top();
		
		echo_title($f_lg['forum_edit']);
		
		echo_forum('edit', $id);
		
		echo_bottom();
		
		break;

// ********************************************************************************
// FORUM ADD && SAVE
// ********************************************************************************
		case "forum_save":
		
        $id       = intval($_GET['id']);
        
		$parentid = intval($_POST['parentid']);
		
		if ($_POST['name'])
		{	
			if (!$id)
            {
                $result_position = $db->super_query("SELECT * FROM " . PREFIX . "_forum_forums WHERE parentid = '$parentid' ORDER BY position DESC LIMIT 1");
                
                $position = $result_position['position'];
                
                if (!$position) $position = '1'; else $position = ($position + 1);
            }
            
            $name = $db->safesql($_POST['name']);
			
			$description = $db->safesql($_POST['description']);
			
			$password = $db->safesql($_POST['password']);
			
			$rules_name = $db->safesql($_POST['rules_name']);
            
            $banner = $db->safesql($_POST['banner']);
            
            $q_reply = intval($_POST['q_reply']);
            
            $i_edit = intval($_POST['i_edit']);
			
			include(ENGINE_DIR.'/classes/parse.class.php');
			
			$parse = new ParseFilter(Array(), Array(), 1, 1);
			
			$rules = $db->safesql($parse->BB_Parse($parse->process($_POST['rules']), false));
			
			$icon = $db->safesql($_POST['icon']);
			
			$postcount = intval($_POST['postcount']);
			
			$fixpost = intval($_POST['fixpost']);
            
            $is_category = intval($_POST['is_category']);
            
            $redirect = $db->safesql($_POST['redirect']);
			
			// ACCESS //
            $access_read = $_POST['access_read'];
			if (!count($access_read)) {$access_read = array (); $access_read[] = '0';}
			$access_read_mysql = $db->safesql(implode(':', $access_read));
			
            $access_write = $_POST['access_write'];
			if (!count($access_write)) {$access_write = array (); $access_write[] = '0';}
			$access_write_mysql = $db->safesql(implode(':', $access_write));
			
            $access_mod = $_POST['access_mod'];
			if (!count($access_mod)) {$access_mod = array (); $access_mod[] = '0';}
			$access_mod_mysql = $db->safesql(implode(':', $access_mod));
			
            $access_topic = $_POST['access_topic'];
			if (!count($access_topic)) {$access_topic = array (); $access_topic[] = '0';}
			$access_topic_mysql = $db->safesql(implode(':', $access_topic));
			
            $access_upload = $_POST['access_upload'];
			if (!count($access_upload)) {$access_upload = array (); $access_upload[] = '0';}
			$access_upload_mysql = $db->safesql(implode(':', $access_upload));
			
            $access_download = $_POST['access_download'];
			if (!count($access_download)) {$access_download = array (); $access_download[] = '0';}
			$access_download_mysql = $db->safesql(implode(':', $access_download));
			
            if ($id)
            {
                $db->query("UPDATE " . PREFIX . "_forum_forums SET parentid = '$parentid', name = '$name', description = '$description', access_read = '$access_read_mysql', access_write = '$access_write_mysql', access_mod = '$access_mod_mysql', access_topic = '$access_topic_mysql', access_upload = '$access_upload_mysql', access_download = '$access_download_mysql', password = '$password', rules_title = '$rules_name', rules = '$rules', icon= '$icon', postcount = '$postcount', fixpost = '$fixpost', banner = '$banner', q_reply = '$q_reply', i_edit = '$i_edit', is_category = '$is_category', redirect = '$redirect' WHERE id = '$id'");
            }
            else
            {
                $db->query("INSERT INTO " . PREFIX . "_forum_forums (parentid, name, description, position, access_read, access_write, access_mod, access_topic, access_upload, access_download, password, rules_title, rules, icon, postcount, fixpost, banner, q_reply, i_edit, is_category, redirect) values ('$parentid', '$name', '$description', '$position', '$access_read_mysql', '$access_write_mysql', '$access_mod_mysql', '$access_topic_mysql', '$access_upload_mysql', '$access_download_mysql', '$password', '$rules_name', '$rules', '$icon', '$postcount', '$fixpost', '$banner', '$q_reply', '$i_edit', '$is_category', '$redirect')");
            }
			
            $dle_forum->cache->delete('forums_array');
            $dle_forum->cache->delete('sub_forums');
			//$dle_forum->cache->clear();
            
            if ($id)
            {
                msg("info",$f_lg['forum_ok_edit1'], $f_lg['forum_ok_edit2'], "?mod=forum&action=content");
            }
            else
            {
                msg("info",$f_lg['forum_ok_add1'], $f_lg['forum_ok_add2'], "?mod=forum&action=content");
            }
		}
		
		else msg("error",$f_lg['error'],$f_lg['forum_err_name'], "javascript:history.go(-1)");
		
		break;
		
// ********************************************************************************
// FORUM DEL
// ********************************************************************************
		case "forum_del":
        
        $id       = intval($_GET['id']);
        
        $parentid = intval($_POST['parentid']);
        
        echo "$id : $parentid";
        
        if ($id && $parentid && $id !== $parentid)
        {
            $db->query("UPDATE " . PREFIX . "_forum_topics SET forum_id = '{$parentid}' WHERE forum_id = '{$id}'");
            
            $db->query("UPDATE " . PREFIX . "_forum_files SET forum_id = '{$parentid}' WHERE forum_id = '{$id}'");
            
            $db->query("UPDATE " . PREFIX . "_forum_forums SET parentid = '{$parentid}' WHERE parentid = '{$id}'");
            
            $db->query("DELETE FROM " . PREFIX . "_forum_forums WHERE id = '$id' LIMIT 1");
            
            $dle_forum->cache->delete('categories');
            $dle_forum->cache->delete('forums_array');
            $dle_forum->cache->delete('sub_forums');
            //$dle_forum->cache->clear();
            
            header("Location: ?mod=forum&action=content");
        }
        else
        {
            echo_top();
            
            echo_title($f_lg['forum_del']);
            
            echo "<form method=\"post\" action=\"{$PHP_SELF}?mod=forum&action=forum_del&id={$id}\">
            <table border=\"0\" width=\"100%\">
            <tr>
            <td width=\"50%\">{$f_lg['forum_del_to']}</td>
            <td><SELECT name=\"parentid\">".$dle_forum->forum_list()."</SELECT></tr>
            <tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
            <tr>
            <td width=\"50%\">&nbsp;</td>
            <td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['java_del']}\"></td></tr>
            </table></form>";
            
            echo_bottom();
        }
		
		break;
		
// ********************************************************************************
// CONTENT
// ********************************************************************************
		case "content":
        case "content_forum":
		
        $id = intval($_GET['id']);
        
        class dle_forum_content
        {
            var $forums_array  = array();
            
            var $moderators    = array();
            
            function dle_forum_content()
            {
                global $db, $dle_forum;
                
                $this->db    =& $db;
                $this->forum =& $dle_forum;
                
                $this->forums_array = $this->forum->get_forums_array();
            }
            
            // INDEX
            function index ()
            {
                global $f_lg;
                
                $this->moderators = $this->forum->get_moderators();
                
                echo_top();
                
                forum_menu();
                
                $position_count = position_count();
                
                $this->forum->get_categories();
                
                echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=forum_sort\">";
                
                while ($row = $this->db->get_row())
                {
                    $compile_forum_list = $this->forum_block($row);
                    
                    // category //
                    echo_top('cat');
                    
                    $category_menu = "<a onClick=\"return dropdownmenu(this, event, MenuCategory('".$row['id']."'), '170px')\" href=\"#\"><img src=\"engine/skins/images/browser_action.gif\" border=\"0\"></a>";
                    
                    $position = category_posi($row['position'], $row['id'], $position_count);
                    
                    echo "<table width='100%' class='navigation'>
                    <tr>
                    <td width='94%' bgcolor='#EFEFEF' height='32' style='padding-left:10px;'><b>".stripcslashes($row['name'])."</b></td>
                    <td width='1%' bgcolor='#EFEFEF' height='32' style='padding-left:10px;'>{$position}</td>
                    <td width='5%' bgcolor='#EFEFEF' height='32' style='padding-left:10px;'>{$category_menu}&nbsp;</td>
                    </tr>
                    </table><div class='unterline'></div>";
                    
                    if (count($compile_forum_list))
                    {
                        foreach ($compile_forum_list as $value)
                        {
                            echo $value;
                        }
                    }
                    
                    echo_bottom('cat');
                    
                    // -- //
                }
                
                echo_top('action');
                
                echo "<div align=\"right\"><a href=\"?mod=forum&action=category\"><input onclick=\"document.location='?mod=forum&action=category'\" class=\"buttons\" style=\"width:150px;\" type=\"button\" value=\"{$f_lg['cat_button']}\"></a>&nbsp;<input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_sort']}\"></div>";
                
                echo "</form>";
                
                echo_bottom('action');
                
                echo_bottom();
            }
            
            // FORUM
            function forum ($id)
            {
                global $f_lg;
                
                if (!$id) return false;
                
                $this->moderators = $this->forum->get_moderators();
                
                echo_top();
                
                echo_top('content');
                
                forum_menu();
                
                $position_count = position_count($id);
                
                $this->forum->get_forums($id);
                
                while ($row = $this->db->get_row())
                {
                    if ($row['id'] == $id)
                    {
                        $forum_title = stripcslashes($row['name']);
                        
                        $compile_forum_list = $this->forum_block($row, $position_count);
                    }
                }
                
                echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=forum_sort&parent_id={$id}\">";
                
                echo_title($forum_title);
                
                if (count($compile_forum_list))
                {
                    foreach ($compile_forum_list as $value)
                    {
                        echo $value;
                    }
                }
                
                echo_bottom('content');
                
                echo_top('action');
                
                echo "<div align=\"right\"><a href=\"?mod=forum&action=forum&parent_id=$id\"><input onclick=\"document.location='?mod=forum&action=forum&sid=$sid&sub_id=$id'\" class=\"buttons\" style=\"width:150px;\" type=\"button\" value=\"{$f_lg['forum_button']}\"></a>&nbsp;<input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_sort']}\"></div>";
                
                echo "</form>";
                
                echo_bottom('action');
                
                echo_bottom();
            }
            
            // FORUM BLOCK
            function forum_block ($row, $position_count = 0)
            {
                $compile_forum_list = array();
                
                if (count($this->forums_array))
                {
                    foreach ($this->forums_array as $forum)
                    {
                        if ($row['id'] == $forum['parentid'])
                        {
                            $forum_list = '';
                            $f_arr      = array();
                    
                            $moderators = '';
                            $m_arr      = array();
                            
                            foreach ($this->forums_array as $sub_forum)
                            {
                                if ($forum['id'] == $sub_forum['parentid'])
                                {
                                    $f_arr[] = "<a href=\"?mod=forum&action=content_forum&id={$sub_forum['parentid']}\">{$sub_forum['name']}</a>";
                                }
                            }
                            
                            if (count($f_arr))
                            {
                                $forum_list = implode(', ', $f_arr);
                            }
                            
                            if ($forum_list)
                            {
                                $forum_list = '<br />&nbsp;-&nbsp;Подфорумы:&nbsp;' . $forum_list;
                            }
                            
                            if ($forum['description'])
                            {
                                $forum['description'] = stripcslashes($forum['description']);
                                
                                $description = "<br /><span class=\"quick\">".$forum['description']."</span>";
                            }
                            else
                            {
                                $description = '';
                            }
                            
                            if ($forum['moderators'] and $this->moderators)
                            {
                                $moderators_id = explode(':', $forum['moderators']);
                                
                                foreach ($moderators_id as $u_id)
                                {
                                    foreach ($this->moderators as $key => $value)
                                    {
                                        if ($this->moderators[$key]['member_id'] == $u_id and $this->moderators[$key]['forum_id'] == $forum['id'])
                                        {
                                            $m_arr[] = "<a onClick=\"return dropdownmenu(this, event, Moderators('".$key."'), '100px')\" href=\"#\">{$this->moderators[$key]['member_name']}</a>&nbsp;";
                                        }
                                    }
                                }
                            }
                            
                            if (count($m_arr))
                            {
                                $moderators = implode(', ', $m_arr);
                            }
                            
                            if ($moderators)
                            {
                                $moderators = "<br />&nbsp;Модераторы:&nbsp;" . $moderators;
                            }
                            
                            $forum_menu = "<a onClick=\"return dropdownmenu(this, event, MenuForum('".$forum['id']."'), '170px')\" href=\"#\"><img src=\"engine/skins/images/browser_action.gif\" border=\"0\"></a>";
                            
                            if ($position_count)
                            {
                                $position = category_posi($forum['position'], $forum['id'], $position_count);
                            }
                            else
                            {
                                $position = '';
                            }
                            
                            $compile_forum_list[] = "<table width='100%' class=\"quick\" cellspacing=1>
                            <tr>
                            <td width='90%' height='32'><b>".stripcslashes($forum['name'])."</b>{$description}{$forum_list}{$moderators}</td>
                            <td width='5%' height='32'>{$position}&nbsp;</td>
                            <td width='5%' height='32'>{$forum_menu}&nbsp;</td>
                            </tr>
                            <tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>
                            </table>";
                            
                            $forum_found = true;
                        }
                    }
                }
                
                return $compile_forum_list;
            }
        }
        
        $dle_forum_content = new dle_forum_content;
        
        if ($action == "content_forum" && $id)
        {
            $dle_forum_content->forum($id);
        }
        else
        {
            $dle_forum_content->index();
        }
		
		break;

// ********************************************************************************
// TOOLS
// ********************************************************************************
		case "tools":

		echo_top();
		
		echo "<form action=\"{$PHP_SELF}?mod=forum&action=tools_save\" method=\"post\">";
	
		require_once ENGINE_DIR.'/forum/admin/tools.php';
		
		echo_top('tools_save');
		
		echo "<input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_save']}\"></form>";
		
		echo_bottom('tools_save');
		
		echo_bottom();
		
		break;
		
// ********************************************************************************
// TOOLS SAVE
// ********************************************************************************
		case "tools_save":
        
        $save_con = $_POST['save_con'];

		$warn_group = $_POST['warn_group'];
        if (!count($warn_group)) {$warn_group = array (); $warn_group[] = '0';}
		$save_con['warn_group'] = $db->safesql(implode(':', $warn_group));
		
        $search_captcha = $_POST['search_captcha'];
		if (!count($search_captcha)) {$search_captcha = array (); $search_captcha[] = '0';}
		$save_con['search_captcha'] = $db->safesql(implode(':', $search_captcha));
		
        $topic_captcha = $_POST['topic_captcha'];
		if (!count($topic_captcha)) {$topic_captcha = array (); $topic_captcha[] = '0';}
		$save_con['topic_captcha'] = $db->safesql(implode(':', $topic_captcha));
		
        $post_captcha = $_POST['post_captcha'];
		if (!count($post_captcha)) {$post_captcha = array (); $post_captcha[] = '0';}
		$save_con['post_captcha'] = $db->safesql(implode(':', $post_captcha));
		
        $tools_upload = $_POST['tools_upload'];
		if (!count($tools_upload)) {$tools_upload = array (); $tools_upload[] = '0';}
		$save_con['tools_upload'] = $db->safesql(implode(':', $tools_upload));
		
        $tools_poll = $_POST['tools_poll'];
		if (!count($tools_poll)) {$tools_poll = array (); $tools_poll[] = '0';}
		$save_con['tools_poll'] = $db->safesql(implode(':', $tools_poll));
		
        $warn_show_group = $_POST['warn_show_group'];
		if (!count($warn_show_group)) {$warn_show_group = array (); $warn_show_group[] = '0';}
		$save_con['warn_show_group'] = $db->safesql(implode(':', $warn_show_group));
		
        $rep_edit_group = $_POST['rep_edit_group'];
		if (!count($rep_edit_group)) {$rep_edit_group = array (); $rep_edit_group[] = '0';}
		$save_con['rep_edit_group'] = $db->safesql(implode(':', $rep_edit_group));
		
		$find[] 	= "'\r'";
		$replace[] 	= "";
		$find[] 	= "'\n'";
		$replace[] 	= "";
		
		$save_con['version_id'] = "2.6.1";
		
		$save_con = $save_con + $forum_config;
		
		$handler = fopen(ENGINE_DIR.'/data/forum_config.php', "w");
		
		fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$forum_config = array (\n\n");
		
		foreach($save_con as $name => $value)
		{
			$value = trim(stripslashes ($value));
			$value = htmlspecialchars ($value, ENT_QUOTES);
			$value = preg_replace($find,$replace,$value);
			fwrite ($handler, "'{$name}' => \"{$value}\",\n\n");
		}
		
		fwrite($handler, ");\n\n?>");
		fclose($handler);
		
		msg("info", $f_lg['t_f_save'], "$f_lg[t_f_save1]<br /><br /><a href=$PHP_SELF?mod=forum>$f_lg[db_prev]</a>");
		
		break;

// ********************************************************************************
// RANK
// ********************************************************************************
		case "rank":
		
		$result = $db->query("SELECT * FROM " . PREFIX . "_forum_titles");
		
		echo_top();
		
		echo_title($f_lg['titles_main']);
		
		echo_rank_style ();
		
		echo "<table width=\"100%\" border=\"0\"><tr>
        <td style=\"padding:2px;\" width=\"25%\" height=\"22\"><b>{$f_lg['titles_name']}</b></td>
        <td width=\"25%\" height=\"24\"><center><b>{$f_lg['titles_pots']}</b></center></td>
        <td width=\"25%\" height=\"24\"><center><b>{$f_lg['titles_pips']}</b></center></td>
        <td width=\"25%\" height=\"24\"><center><b>{$f_lg['titles_action']}<b></center></td></tr>
		</table><div class='unterline'></div><table width=\"100%\" border=\"0\">";
		
		while ($row = $db->get_row($result))
		{
			$t_action = "[<a href=\"$PHP_SELF?mod=forum&action=rank_edit&id={$row['id']}\">{$f_lg['label_edit']}</a>]"." [<a href=\"$PHP_SELF?mod=forum&action=rank_del&id={$row['id']}\">{$f_lg['label_del']}</a>]";
			
			$rating = $row['pips'] * 17;
			
			$rank_image = "<div class=\"rank\" style=\"display:inline;\">
			<ul class=\"unit-rank\">
			<li class=\"current-rank\" style=\"width:{$rating}px;\">{$rating}</li>
			</ul>
			</div>";
			
			echo "<tr>
			<td style=\"padding:2px;\" width=\"25%\" height=\"22\">{$row['title']}</td>
			<td width=\"25%\" height=\"24\"><center>{$row['posts']}</center></td>
			<td width=\"25%\" height=\"24\"><center>{$rank_image}</center></td>
			<td width=\"25%\" height=\"24\"><center>{$t_action}</center></td></tr>
			<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=7></td></tr>";
		}
		
		echo "</table>";
		
		echo_bottom('w');
		
		echo_top('w');
		
		echo_title($f_lg['titles_uadd']);
		
		echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=rank_user\">
		<table border=\"0\" width=\"100%\">
		<tr>
		<td width=\"260\">{$f_lg['titles_uname']}</td>
		<td><input class=\"edit\" type=\"text\" name=\"user_name\" value=\"\" size=\"27\"></td></tr>
		<tr>
		<td width=\"260\">{$f_lg['titles_urname']}</td>
		<td><input class=\"edit\" type=\"text\" name=\"user_rank\" value=\"\" size=\"27\"></td></tr>
		<tr>
		<td width=\"260\">{$f_lg['titles_npips']}</td>
		<td><input class=\"edit\" type=\"text\" name=\"user_pips\" value=\"\" size=\"27\"></td></tr>
		<tr><td colspan=\"2\"><div class=\"hr_line\"></div></td></tr>
		<tr>
		<td width=\"260\">&nbsp;</td>
		<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_add']}\"></td></tr>
		</table></form>";
		
		echo_bottom('w');
		
		echo_top('w');
		
		echo_title($f_lg['titles_add']);
		
		echo_rank('new');
		
		echo_bottom();
		
		break;

// ********************************************************************************
// RANK ADD
// ********************************************************************************
		case "rank_add":
		
        $t_name  = $db->safesql($_POST['t_name']);
        $t_posts = intval($_POST['t_posts']);
        $t_pips  = intval($_POST['t_pips']);
        
		if ($t_name)
		{
			$db->query("INSERT INTO " . PREFIX . "_forum_titles (posts, title, pips) values ('$t_posts', '$t_name', '$t_pips')");
			
			$dle_forum->cache->delete('rank_array');
			
			header("Location: ?mod=forum&action=rank");
		}
		
		break;

// ********************************************************************************
// RANK EDIT
// ********************************************************************************
		case "rank_edit":
		
        $id = intval($_GET['id']);
        
		echo_top();
		
		echo_title($f_lg['titles_edit']);
		
		echo_rank('edit', $id);
		
		echo_bottom();
		
		break;

// ********************************************************************************
// RANK SAVE
// ********************************************************************************
		case "rank_save":
		
        $id      = intval($_GET['id']);
        $t_name  = $db->safesql($_POST['t_name']);
        $t_posts = intval($_POST['t_posts']);
        $t_pips  = intval($_POST['t_pips']);
        
		if ($t_name and $id)
		{
			$db->query("UPDATE " . PREFIX . "_forum_titles SET title = '$t_name', posts = '$t_posts', pips = '$t_pips' WHERE id = '$id'");
			
			$dle_forum->cache->delete('rank_array');
			
			header("Location: ?mod=forum&action=rank");
		}
		
		break;

// ********************************************************************************
// RANK DEL
// ********************************************************************************
		case "rank_del":
        
        $id = intval($_GET['id']);
		
		$db->query("DELETE FROM " . PREFIX . "_forum_titles WHERE id = '$id' LIMIT 1");
		
		$dle_forum->cache->delete('rank_array');
		
		header("Location: ?mod=forum&action=rank");
		
		break;

// ********************************************************************************
// RANK USER SAVE
// ********************************************************************************
		case "rank_user":
		
        $user_name = $db->safesql($_POST['user_name']);
        $user_rank = $db->safesql($_POST['user_rank']);
		$user_pips = intval($_POST['user_pips']);
		
		$db->query("SELECT * FROM " . USERPREFIX . "_users where name = '$user_name'");
		
		if ($db->num_rows())
		{
			$db->query("UPDATE " . PREFIX . "_users SET forum_rank = '$user_rank', forum_pips = '$user_pips' WHERE name = '$user_name'");
			
			header("Location: ?mod=forum&action=rank");
		} else 
		{
			msg("info",$f_lg['error'], $f_lg['titles_error_name'], "javascript:history.go(-1)");
		}
		
		break;
		
// ********************************************************************************
// NEW MODERATOR
// ********************************************************************************
		case "moderator_new":
		
        $id         = intval($_GET['id']);
        $user_found = $db->safesql($_POST['user_found']);
        
		if ($user_found)
		{
			$user_found = $db->super_query("SELECT * FROM " . USERPREFIX . "_users WHERE name = '$user_found'");
			
			$uid = $user_found['user_id'];
		}
		
		if ($user_found['name'])
		{
			echo_top();
			
			echo_title($f_lg['mod_config_set']);
			
			echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=moderator_add&fid=$id&uid=$uid\">";
			
			echo "<table width=\"100%\">";
			
			require_once ENGINE_DIR.'/forum/admin/moderation.php';
			
			echo "</table>";
			
			echo "<br /><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_save']}\">";
			
			echo "</form>";
			
			echo_bottom();
		}
		
		else
		{
			echo_top();
			
			echo_title($f_lg['mod_search_user']);
			
			echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=moderator_new&id=$id\">";
			
			echo "<table border=\"0\" width=\"100%\">
			<tr>
			<td style=\"padding:4px\" class=\"option\" width=\"260\">{$f_lg['mod_search_name']}</td>
			<td><input class=\"edit\" type=\"text\" name=\"user_found\" value=\"\" size=\"27\"></td></tr>
			<tr>
			<td width=\"260\">&nbsp;</td>
			<td><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_search']}\"></td></tr>
			</table>";
			
			echo "</form>";
			
			echo_bottom();
		}
		
		break;

// ********************************************************************************
// MODERATOR ADD
// ********************************************************************************
		case "moderator_add":
		
        $uid  = intval($_GET['uid']);
        $fid  = intval($_GET['fid']);
        $save = $_POST['save'];
        
		$user_found = $db->super_query("SELECT * FROM " . USERPREFIX . "_users WHERE user_id = '$uid'");
		
		if ($user_found['user_id'] and $fid)
		{
			$row = $db->super_query("SELECT * FROM " . PREFIX . "_forum_moderators WHERE member_id = '$uid' and forum_id = '$fid'");
			
			$moderator_id = $row['mid'];
			
			if (!$moderator_id)
			{
				$db->query("INSERT INTO " . PREFIX . "_forum_moderators (member_id, member_name, forum_id) values ('$uid', '$user_found[name]', '$fid')");
				
				$moderator_id = $db->insert_id();
			}
			
			$db->query("UPDATE " . PREFIX . "_forum_moderators SET edit_post = '$save[edit_post]', delete_topic = '$save[delete_topic]', edit_topic = '$save[edit_topic]', edit_post = '$save[edit_post]', delete_post = '$save[delete_post]', open_topic = '$save[open_topic]', close_topic = '$save[close_topic]', delete_post = '$save[delete_post]', move_topic = '$save[move_topic]', pin_topic = '$save[pin_topic]', delete_topic = '$save[delete_topic]', unpin_topic = '$save[unpin_topic]', allow_warn = '$save[allow_warn]', mass_prune = '$save[mass_prune]', combining_post = '$save[combining_post]', move_post = '$save[move_post]', read_mode = '$save[read_mode]', banned = '$save[banned]' WHERE mid = '$moderator_id'");
			
			$new_row = $db->query("SELECT mid, member_id FROM " . PREFIX . "_forum_moderators WHERE forum_id = '$fid'");
			
			while ($row = $db->get_row($new_row))
			{
				$update_uid[$row['member_id']] = $row['member_id'];
			}
			
			$update_uid = implode(':', $update_uid);
			
			$db->query("UPDATE " . PREFIX . "_forum_forums SET moderators = '$update_uid' WHERE id = '$fid'");
			
			$dle_forum->cache->delete('forum_moderators');
			$dle_forum->cache->delete('forums_array');
            //$dle_forum->cache->clear();
			
			msg("info",$f_lg['mod_add'], $f_lg['mod_add2'], "?mod=forum&action=content");
		}
		
		break;

// ********************************************************************************
// MODERATOR EDIT
// ********************************************************************************
		case "moderator_edit":
		
        $id = intval($_GET['id']);
        
		if ($id)
		{
			$row = $db->super_query("SELECT * FROM " . PREFIX . "_forum_moderators WHERE mid = '$id'");
			
			$moderator_edit = true;
			
			echo_top();
			
			echo_title($f_lg['mod_config_set']);
			
			echo "<form method=\"post\" action=\"$PHP_SELF?mod=forum&action=moderator_save&id=$id\">";
			
			echo "<table width=\"100%\">";
			
			require_once ENGINE_DIR.'/forum/admin/moderation.php';
			
			echo "</table>";
			
			echo "<br /><input type=\"submit\" class=\"buttons\" value=\"{$f_lg['button_edit']}\">";
			
			echo "</form>";
			
			echo_bottom();
		}
		
		break;

// ********************************************************************************
// MODERATOR SAVE
// ********************************************************************************
		case "moderator_save":
		
        $id   = intval($_GET['id']);
        
        $save = $_POST['save'];
        
		if ($id)
		{
			$db->query("UPDATE " . PREFIX . "_forum_moderators SET edit_post = '$save[edit_post]', delete_topic = '$save[delete_topic]', edit_topic = '$save[edit_topic]', edit_post = '$save[edit_post]', delete_post = '$save[delete_post]', open_topic = '$save[open_topic]', close_topic = '$save[close_topic]', delete_post = '$save[delete_post]', move_topic = '$save[move_topic]', pin_topic = '$save[pin_topic]', delete_topic = '$save[delete_topic]', unpin_topic = '$save[unpin_topic]', allow_warn = '$save[allow_warn]', mass_prune = '$save[mass_prune]', read_mode = '$save[read_mode]', banned = '$save[banned]' WHERE mid = '$id'");
			
			$dle_forum->cache->delete('forum_moderators');
			
			msg("info",$f_lg['mod_edit_ok'], $f_lg['mod_edit_ok2'], "?mod=forum&action=content");
		}
		
		break;

// ********************************************************************************
// MODERATOR DEL
// ********************************************************************************
		case "moderator_del":
		
        $id   = intval($_GET['id']);
        
		if ($id)
		{
			$row = $db->super_query("SELECT * FROM " . PREFIX . "_forum_moderators WHERE mid = '$id'");
			
			$fid = $row['forum_id'];
			
			if ($row['member_id'])
			{
				$db->query("DELETE FROM " . PREFIX . "_forum_moderators WHERE mid = '$id'");
				
				$row_forum = $db->super_query("SELECT moderators FROM " . PREFIX . "_forum_forums WHERE id = '$fid'");
				
				$new_row = $db->query("SELECT mid, member_id FROM " . PREFIX . "_forum_moderators WHERE forum_id = '$fid'");
				
				while ($row = $db->get_row($new_row))
				{
					$update_uid[$row['member_id']] = $row['member_id'];
				}
				
				$update_uid = implode(':', $update_uid);
				
				$db->query("UPDATE " . PREFIX . "_forum_forums SET moderators = '$update_uid' WHERE id = '$fid'");
				
				$dle_forum->cache->delete('forum_moderators');
				$dle_forum->cache->delete('forums_array');
                //$dle_forum->cache->clear();
				
				header("Location: ?mod=forum&action=content");
			}
		}
		
		break;

// ********************************************************************************
// HELP
// ********************************************************************************
		case "help":
		
		echoheader("", "");
		
		echo_top('about');
		
		echo_title("DLE Forum Version 2.6.1 Build 1");
		
		$licence_file = @file_get_contents('http://dlekey.cn/extras/licence.odf');
        
        if (!$licence_file) { $licence_file = "<a href='http://dlekey.cn/dle_forum_license.html'>http://dlekey.cn/dle_forum_license.html</a>"; }
		
		echo "<div class=\"quick\">{$licence_file}</div>";
		
		echo_bottom('about');
		
		echo_top('authors');
		
		echo_title("Developers");
		
		echo "<div class=\"quick\">";
		
		echo "<b>Author & Developer:</b>&nbsp;&nbsp;Vadim Shestakov [ShVad]<br /><br />";
		
		echo "<hr><br / >";
		
		echo "<center>Copyright 2011 &copy; <a href=\"http://www.dlekey.cn\" target=\"_blank\">DLE Files Group</a>. All rights reserved.</center>";
		
		echo "</div>";
		
		echo_bottom('authors');
		
		echofooter();
		
		break;
		
// ********************************************************************************
// EMAIL
// ********************************************************************************
		case "email":
		
		require_once ENGINE_DIR.'/forum/admin/email.php';
		
		break;
		
// ********************************************************************************
// USER GROUP
// ********************************************************************************
		case "usergroup":
		
		require_once ENGINE_DIR.'/forum/admin/usergroup.php';
		
		break;
		
// ********************************************************************************
// SERVICE
// ********************************************************************************
		case "service":
		
		require_once ENGINE_DIR.'/forum/admin/service.php';
		
		break;
		
// ********************************************************************************
// DISCUSS
// ********************************************************************************
		case "discuss":
		
		require_once ENGINE_DIR.'/forum/admin/discuss.php';
		
		break;
		
// ********************************************************************************
// CLEAR CACHE
// ********************************************************************************
		case "clear":
		
		$dle_forum->cache->delete();
		
		$dle_forum->cache->clear();
		
		header("Location: ?mod=forum");
		
		break;
	}
	
?>