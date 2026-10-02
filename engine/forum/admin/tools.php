<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: tools.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

// ********************************************************************************
// showRow
// ********************************************************************************
	function showRow($title="", $description="", $field="")
	{
		echo"<tr>
		<td style=\"padding:4px\" class=\"option\">
		<b>$title</b><br /><span class=small>$description</span>
		<td width=394 align=middle >$field
		</tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=2></td></tr>";
		$bg = ""; $i++;
	}

echo <<<HTML
<script language='JavaScript' type="text/javascript">

        function ChangeOption(selectedOption) {

                document.getElementById('global').style.display = "none";
                document.getElementById('show').style.display = "none";
                document.getElementById('safety').style.display = "none";
                document.getElementById('preventions').style.display = "none";
                document.getElementById('modules').style.display = "none";
                document.getElementById('discuss').style.display = "none";
                document.getElementById('speed').style.display = "none";
                document.getElementById('uploads').style.display = "none";
                document.getElementById('licence').style.display = "none";

                if(selectedOption == 'global') {document.getElementById('global').style.display = "";}
                if(selectedOption == 'show') {document.getElementById('show').style.display = "";}
                if(selectedOption == 'safety') {document.getElementById('safety').style.display = "";}
                if(selectedOption == 'preventions') {document.getElementById('preventions').style.display = "";}
                if(selectedOption == 'modules') {document.getElementById('modules').style.display = "";}
                if(selectedOption == 'discuss') {document.getElementById('discuss').style.display = "";}
                if(selectedOption == 'speed') {document.getElementById('speed').style.display = "";}
                if(selectedOption == 'uploads') {document.getElementById('uploads').style.display = "";}
                if(selectedOption == 'licence') {document.getElementById('licence').style.display = "";}


       }

</script>
HTML;
	
	// TOOLS MENU //
	
	echo_top('tools_menu');
	
	echo_title($f_lg['tools_menu']);
	
echo <<<HTML
<table width="100%">
    <tr>
        <td style="padding:2px;">
<table style="text-align:center;" width="100%" height="35px">
<tr style="vertical-align:middle;" >
 <td class=tableborder><a href="javascript:ChangeOption('global');"><img title="$f_lg[tools_global]" src="engine/forum/admin/ico/global.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('show');"><img title="$f_lg[tools_show]" src="engine/forum/admin/ico/show.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('safety');"><img title="$f_lg[tools_safety]" src="engine/forum/admin/ico/safety.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('preventions');"><img title="$f_lg[tools_preventions]" src="engine/forum/admin/ico/preventions.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('modules');"><img title="$f_lg[tools_modules]" src="engine/forum/admin/ico/modules.png" border="0"></a>
 
 <td class=tableborder><a href="javascript:ChangeOption('discuss');"><img title="$f_lg[tools_discuss]" src="engine/forum/admin/ico/news.png" border="0"></a>
 
 <td class=tableborder><a href="javascript:ChangeOption('speed');"><img title="$f_lg[tools_speed]" src="engine/forum/admin/ico/speed.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('uploads');"><img title="$f_lg[tools_uploads]" src="engine/forum/admin/ico/uploads.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('licence');"><img title="$f_lg[tools_licence]" src="engine/forum/admin/ico/licence.png" border="0"></a>
 </tr>
</table>
</td>
    </tr>
</table>
HTML;
	
	echo_bottom('tools_menu');
	
// ********************************************************************************
// TOOLS
// ********************************************************************************
	
	// globals tools
	
	echo "<div style='' id=\"global\">";
	
	echo_top('tools_global');
	
	echo_title($f_lg['tools_global']);
	
	echo "<table width=\"100%\">";
	
	showRow($f_lg['tools_name'], $f_lg['tools_name_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[forum_title]' value='{$forum_config['forum_title']}' size=40>");
	
	showRow($f_lg['tools_url'], $f_lg['tools_url_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[forum_url]' value='{$forum_config['forum_url']}' size=40>");
	
	showRow($f_lg['meta_description'], $f_lg['meta_description2'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[meta_descr]' value='{$forum_config['meta_descr']}' size=40>");
	
	showRow($f_lg['meta_keywords'], $f_lg['meta_keywords2'], "<textarea class=\"edit\" style=\"width:250px;height:50px;\" name='save_con[meta_keywords]'>{$forum_config['meta_keywords']}</textarea>");
	
	showRow($f_lg['meta_topic'], $f_lg['meta_topic2'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[meta_topic]", "{$forum_config['meta_topic']}"));
	
    showRow($f_lg['sep_subforum'], $f_lg['sep_subforum2'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[sep_subforum]' value=\"{$forum_config['sep_subforum']}\" size=10>");
    
    showRow($f_lg['sep_moderators'], $f_lg['sep_moderators2'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[sep_moderators]' value=\"{$forum_config['sep_moderators']}\" size=10>");
    
	showRow($f_lg['tools_abc_last'], $f_lg['tools_abc_last1'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[last_abc]' value=\"{$forum_config['last_abc']}\" size=10>");
	
	showRow($f_lg['tools_mrewrite'], $f_lg['tools_mrewrite_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[mod_rewrite]", "{$forum_config['mod_rewrite']}"));
	
	showRow($f_lg['tools_wysiwyg'], $f_lg['tools_wysiwyg2'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[wysiwyg]", "{$forum_config['wysiwyg']}"));
	
	showRow($f_lg['tools_offline'], $f_lg['tools_offline_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[offline]", "{$forum_config['offline']}"));
	
	showRow($f_lg['tools_timestamp'], $f_lg['tools_timestamp2'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[timestamp]' value='{$forum_config['timestamp']}' size=40>");
	
	showRow($f_lg['tools_sessions'], $f_lg['tools_sessions_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[sessions_log]", "{$forum_config['sessions_log']}"));
	
	showRow($f_lg['tools_ses_time'], $f_lg['tools_ses_time_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[session_time]' value=\"{$forum_config['session_time']}\" size=10>");
	
	showRow($f_lg['tools_stats'], $f_lg['tools_stats_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[stats]", "{$forum_config['stats']}"));
	
	showRow($f_lg['tools_online'], $f_lg['tools_online_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[online]", "{$forum_config['online']}"));
	
	showRow($f_lg['tools_forum_bar'], $f_lg['tools_forum_bar_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[forum_bar]", "{$forum_config['forum_bar']}"));
	
	echo "</table>";
	
	echo_bottom('tools_global');
	
	echo "</div>";
	
	// topics, posts
	
	echo "<div style='display:none' id=\"show\">";
	
	echo_top('tools_show');
	
	echo_title($f_lg['tools_show']);
	
	echo "<table style='' id=\"tools_show\" width=\"100%\">";
	
	showRow($f_lg['tools_topics'], $f_lg['tools_topics_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[topic_inpage]' value=\"{$forum_config['topic_inpage']}\" size=10>");
	
	showRow($f_lg['tools_hot'], $f_lg['tools_hot_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[topic_hot]' value=\"{$forum_config['topic_hot']}\" size=10>");
	
	showRow($f_lg['tools_posts'], $f_lg['tools_posts_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[post_inpage]' value=\"{$forum_config['post_inpage']}\" size=10>");
	
	showRow($f_lg['tools_mhide'], $f_lg['tools_mhide_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[post_hide]' value=\"{$forum_config['post_hide']}\" size=10>");
	
	showRow($f_lg['tools_abc_topic'], $f_lg['tools_abc_topic_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[topic_abc]' value=\"{$forum_config['topic_abc']}\" size=10>");
	
	showRow($f_lg['tools_post_maxlen'], $f_lg['tools_post_maxlen2'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[post_maxlen]' value=\"{$forum_config['post_maxlen']}\" size=10>");
	
	showRow($f_lg['tools_auto_wrap'], $f_lg['tools_auto_wrap2'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[auto_wrap]' value=\"{$forum_config['auto_wrap']}\" size=10>");
	
	showRow($f_lg['tools_post_update'], $f_lg['tools_post_update2'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[post_update]", "{$forum_config['post_update']}"));
	
	showRow($f_lg['tools_last_plink'], $f_lg['tools_last_plink_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[last_plink]", "{$forum_config['last_plink']}"));
	
	showRow($f_lg['tools_hide_forum'], $f_lg['tools_hide_forum_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[hide_forum]", "{$forum_config['hide_forum']}"));
	
	showRow($f_lg['tools_topic_sort'], $f_lg['tools_topic_sort_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[topic_sort]", "{$forum_config['topic_sort']}"));
	
	showRow($f_lg['tools_topic_email'], $f_lg['tools_topic_email_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[topic_email]", "{$forum_config['topic_email']}"));
	
	showRow($f_lg['tools_pr_imp'], $f_lg['tools_pr_imp_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[forum_pr_imp]' value='{$forum_config['forum_pr_imp']}' size=40>");
	
	showRow($f_lg['tools_pr_vote'], $f_lg['tools_pr_vote_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[forum_pr_vote]' value='{$forum_config['forum_pr_vote']}' size=40>");
	
	showRow($f_lg['tools_pr_modr'], $f_lg['tools_pr_modr_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[forum_pr_modr]' value='{$forum_config['forum_pr_modr']}' size=40>");
	
	showRow($f_lg['tools_pr_sub_f'], $f_lg['tools_pr_sub_f_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[forum_pr_sub]' value='{$forum_config['forum_pr_sub']}' size=40>");
	
	echo "</table>";
	
	echo_bottom('tools_show');
	
	echo "</div>";
	
	// safety
	
	echo "<div style='display:none' id=\"safety\">";
	
	echo_top('tools_safety');
	
	echo_title($f_lg['tools_safety']);
	
	echo "<table width=\"100%\">";
	
	showRow($f_lg['tools_complaint'], $f_lg['tools_complaint_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[mod_report]", "{$forum_config['mod_report']}"));
	
	showRow($f_lg['tools_flood'], $f_lg['tools_flood_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[flood_time]' value=\"{$forum_config['flood_time']}\" size=10>");
	
	showRow($f_lg['tools_search_captcha'], $f_lg['tools_search_captcha_'], "<select name=\"search_captcha[]\" multiple>".warn_group($forum_config['search_captcha'])."</select>");
	
	showRow($f_lg['tools_topic_captcha'], $f_lg['tools_topic_captcha1'], "<select name=\"topic_captcha[]\" multiple>".warn_group($forum_config['topic_captcha'])."</select>");
	
	showRow($f_lg['tools_post_captcha'], $f_lg['tools_post_captcha1'], "<select name=\"post_captcha[]\" multiple>".warn_group($forum_config['post_captcha'])."</select>");
	
	echo "</table>";
	
	echo_bottom('tools_safety');
	
	echo "</div>";
	
	// System of Preventions
	
	echo "<div style='display:none' id=\"preventions\">";
	
	echo_top('tools_preventions');
	
	echo_title($f_lg['tools_preventions']);
	
	echo "<table width=\"100%\">";
	
	showRow($f_lg['tools_prevntn_on'], $f_lg['tools_prevntn_on_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[warn]", "{$forum_config['warn']}"));
	
	showRow($f_lg['tools_prevntn_max'], $f_lg['tools_prevntn_max_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[warn_max]' value=\"{$forum_config['warn_max']}\" size=10>");
	
	showRow($f_lg['tools_prevntn_group'], $f_lg['tools_prevntn_group_'], "<select name=\"warn_group[]\" multiple>".warn_group($forum_config['warn_group'])."</select>");
	
	showRow($f_lg['tools_prevntn_g_show'], $f_lg['tools_prevntn_g_show_'], "<select name=\"warn_show_group[]\" multiple>".warn_group($forum_config['warn_show_group'])."</select>");
	
	showRow($f_lg['tools_warn_day'], $f_lg['tools_warn_day2'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[warn_day]' value=\"{$forum_config['warn_day']}\" size=10>");
	
	showRow($f_lg['tools_prevntn_show'], $f_lg['tools_prevntn_show_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[warn_show]", "{$forum_config['warn_show']}"));
	
	showRow($f_lg['tools_prevntn_show_all'], $f_lg['tools_prevntn_show_all_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[warn_show_all]", "{$forum_config['warn_show_all']}"));
	
	showRow($f_lg['tools_prevntn_show_gr'], $f_lg['tools_prevntn_show_gr_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[warn_sh_pg]", "{$forum_config['warn_sh_pg']}"));
	
	echo "</table>";
	
	echo_bottom('tools_preventions');
	
	echo "</div>";
	
	// modules
	
	echo "<div style='display:none' id=\"modules\">";
	
	echo_top('tools_modules');
	
	echo_title($f_lg['tools_modules']);
	
	echo "<table width=\"100%\">";
	
	showRow($f_lg['tools_subscr'], $f_lg['tools_subscr_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[subscription]", "{$forum_config['subscription']}"));
	
	showRow($f_lg['tools_mod_icq'], $f_lg['tools_mod_icq_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[mod_icq]", "{$forum_config['mod_icq']}"));
	
	showRow($f_lg['tools_mod_rank'], $f_lg['tools_mod_rank_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[mod_rank]", "{$forum_config['mod_rank']}"));
	
	showRow($f_lg['tools_reputation'], $f_lg['tools_reputation_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[reputation]", "{$forum_config['reputation']}"));
	
	showRow($f_lg['rep_edit_group'], $f_lg['rep_edit_group2'], "<select name=\"rep_edit_group[]\" multiple>".warn_group($forum_config['rep_edit_group'])."</select>");
	
	showRow($f_lg['tools_poll'], $f_lg['tools_poll_'], "<select name=\"tools_poll[]\" multiple>".warn_group($forum_config['tools_poll'])."</select>");
	
	showRow($f_lg['tools_ses_forum'], $f_lg['tools_ses_forum_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[ses_forum]", "{$forum_config['ses_forum']}"));
	
	showRow($f_lg['tools_ses_topic'], $f_lg['tools_ses_topic_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[ses_topic]", "{$forum_config['ses_topic']}"));
    
    showRow($f_lg['tools_bot_agent'], $f_lg['tools_bot_agent2'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[bot_agent]", "{$forum_config['bot_agent']}"));
	
	echo "</table>";
	
	echo_bottom('tools_modules');
	
	echo "</div>";
	
	// discuss
	
	echo "<div style='display:none' id=\"discuss\">";
	
	echo_top('tools_discuss');
	
	echo_title($f_lg['tools_discuss']);
	
	echo "<table width=\"100%\">";
	
	showRow($f_lg['tools_disc_on'], $f_lg['tools_disc_on_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[discuss]", "{$forum_config['discuss']}"));
	
	showRow($f_lg['tools_disc_title'], $f_lg['tools_disc_title_'], makeDropDown(array("1"=>$f_lg['tools_disc_opt_1'],"0"=>$f_lg['tools_disc_opt_2']), "save_con[discuss_title]", "{$forum_config['discuss_title']}"));
	
	showRow($f_lg['tools_disc_t_tpl'], $f_lg['tools_disc_t_tpl_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[discuss_title_tpl]' value='{$forum_config['discuss_title_tpl']}' size=40>");
	
	showRow($f_lg['tools_disc_post'], $f_lg['tools_disc_post_'], makeDropDown(array("1"=>$f_lg['tools_disc_opt_3'],"2"=>$f_lg['tools_disc_opt_4'],"3"=>$f_lg['tools_disc_opt_5']), "save_con[tools_disc_post]", "{$forum_config['tools_disc_post']}"));
	
	showRow($f_lg['tools_disc_p_tpl'], $f_lg['tools_disc_p_tpl_'], "<textarea class=\"edit\" style=\"width:250px;height:50px;\" name='save_con[discuss_post_tpl]'>{$forum_config['discuss_post_tpl']}</textarea>");
	
	echo "</table>";
	
	echo_bottom('tools_discuss');
	
	echo "</div>";
	
	// speed
	
	echo "<div style='display:none' id=\"speed\">";
	
	echo_top('tools_speed');
	
	echo_title($f_lg['tools_speed']);
	
	echo "<table width=\"100%\">";
	
	showRow($f_lg['tools_t_as_p'], $f_lg['tools_t_as_p_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[set_topic_post]", "{$forum_config['set_topic_post']}"));
	
	showRow($f_lg['tools_sp_num'], $f_lg['tools_sp_num_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[set_post_num_up]", "{$forum_config['set_post_num_up']}"));
	
	showRow($f_lg['tools_sp_num_date'], $f_lg['tools_sp_num_date_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[set_post_num_day]' value=\"{$forum_config['set_post_num_day']}\" size=10>");
	
	showRow($f_lg['tools_new_t_day'], $f_lg['tools_new_t_day_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[topic_new_day]' value=\"{$forum_config['topic_new_day']}\" size=10>");
	
	showRow($f_lg['tools_sp_sublast'], $f_lg['tools_sp_sublast_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[set_sub_last_up]", "{$forum_config['set_sub_last_up']}"));
	
	echo "</table>";
	
	echo_bottom('tools_speed');
	
	echo "</div>";
	
	// uploads
	
	echo "<div style='display:none' id=\"uploads\">";
	
	echo_top('tools_uploads');
	
	echo_title($f_lg['tools_uploads']);
	
	echo "<table width=\"100%\">";
	
	//showRow($f_lg['tools_upload'], $f_lg['tools_upload_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[tools_upload]", "{$forum_config['tools_upload']}"));
	
	showRow($f_lg['tools_upload'], $f_lg['tools_upload_'], "<select name=\"tools_upload[]\" multiple>".warn_group($forum_config['tools_upload'])."</select>");
	
	showRow($f_lg['tools_upload_type'], $f_lg['tools_upload_type_'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[upload_type]' value='{$forum_config['upload_type']}' size=40>");
	
	showRow($f_lg['tools_img_upl'], $f_lg['tools_img_upl_'], makeDropDown(array("1"=>$lang['opt_sys_yes'],"0"=>$lang['opt_sys_no']), "save_con[img_upload]", "{$forum_config['img_upload']}"));
	
	showRow($f_lg['tools_img_max_size'], $f_lg['tools_img_max_size_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[img_size]' value=\"{$forum_config['img_size']}\" size=10>");
	
	showRow($f_lg['tools_thumb_size'], $f_lg['tools_thumb_size_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[thumb_size]' value=\"{$forum_config['thumb_size']}\" size=10>");
	
	showRow($f_lg['tools_jpeg_quality'], $f_lg['tools_jpeg_quality_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[jpeg_quality]' value=\"{$forum_config['jpeg_quality']}\" size=10>");
	
	showRow($f_lg['tools_img_width'], $f_lg['tools_img_width_'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[tag_img_width]' value=\"{$forum_config['tag_img_width']}\" size=10>");
	
	echo "</table>";
	
	echo_bottom('tools_uploads');
	
	echo "</div>";
	
	// licence
	
	echo "<div style='display:none' id=\"licence\">";
	
	echo_top('tools_licence');
	
	echo_title($f_lg['tools_licence']);
	
	echo "<table width=\"100%\">";
	
	if ($l_full)
	{
		js_forum_activation();
		
		$tools_licence = "<div id=\"forum-activation\"><input class=\"edit\" type=\"text\" size=\"36\" name=\"forum_key\" id=\"forum_key\"> <input class=\"edit\" type=\"button\" onClick=\"forum_activation(); return false;\" value=\"->\"></div>";
		
		showRow($f_lg['tools_licence_key'], $f_lg['tools_licence_key_'], $tools_licence);
	}
	
	else
	{
		showRow($f_lg['tools_copyright'], $f_lg['tools_copyright2'], makeDropDown(array("0"=>$lang['opt_sys_yes'],"1"=>$lang['opt_sys_no']), "save_con[copyright]", "{$forum_config['copyright']}"));
	}
	
	echo "</table>";
	
	echo_bottom('tools_licence');
	
	echo "</div>";
	
?>