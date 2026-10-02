<?php
if (!defined('DATALIFEENGINE')) exit('No direct script access allowed');
class DLE_Forum
{
function DLE_Forum ()
{
global $db;
$this->db =&$db;
}
function compile ()
{
global $tpl,$fcache,
$config,$forum_config,$f_lang,$tpl_dir,$is_logged,$metatags,
$member_id,$_TIME,$dle_forum_last_visit,
$a_forum_url,$forum_link_array,$forum_bar_array,
$forum_id,$fid;
$this->tpl =&$tpl;
$powered_by = '';
$forum_bar = ($forum_config['forum_bar']) ?implode(' &raquo; ',$forum_bar_array) : '';
$forum_id = ($forum_id) ?$forum_id : $fid;
$forum_content = array(
'{BOARD HEADER}'=>$forum_bar,
'{last_visit}'=>$dle_forum_last_visit,
'{now_time}'=>langdate ($forum_config['timestamp'],$_TIME),
'{STATS}'=>$this->tpl->result['forum_stats'],
'[search-link]'=>"<a href=\"{$forum_link_array['search']}\">",
'[/search-link]'=>'</a>',
'[getnew-link]'=>"<a href=\"{$forum_link_array['getnew']}\">",
'[/getnew-link]'=>'</a>',
'[topics-link]'=>"<a href=\"{$a_forum_url}act=getforum&amp;code=user&amp;n={$member_id['name']}\">",
'[/topics-link]'=>'</a>',
'[posts-link]'=>"<a href=\"{$a_forum_url}act=posts&amp;user={$member_id['name']}\">",
'[/posts-link]'=>'</a>',
'[subscription-link]'=>"<a href=\"{$forum_link_array['subscription']}\">",
'[/subscription-link]'=>'</a>',
'[textversion]'=>"<a href=\"{$a_forum_url}act=textversion\">",
'[/textversion]'=>'</a>',
'[fullversion]'=>"<a href=\"{$a_forum_url}act=fullversion\">",
'[/fullversion]'=>'</a>',
'[rss]'=>"<a href=\"{$a_forum_url}act=rss&amp;forum_id={$forum_id}\">",
'[/rss]'=>'</a>',
);
$forum_ajax = "\r\n<script language=\"javascript\" type=\"text/javascript\">\r\n".
"var site_dir      = '{$config['http_home_url']}';\r\n".
"var forum_ajax    = '".forum_base_dir."engine/forum/ajax/';\r\n".
"var forum_wysiwyg = '{$forum_config['wysiwyg']}';\r\n".
"</script>\r\n";
if ($config['version_id'] <'9.0')
{
$forum_ajax .= "<script type='text/javascript' src='{$config['http_home_url']}engine/forum/sources/8.5/dle85.js'></script>\r\n".
"<link rel=\"stylesheet\" type=\"text/css\" href=\"{$config['http_home_url']}engine/forum/sources/8.5/dle85.css\" />\r\n";
}
if ($config['version_id'] == '9.0')
{
$forum_ajax .= "<script type='text/javascript' src='{$config['http_home_url']}engine/forum/ajax/dle.js'></script>\r\n";
}
$this->tpl->load_template($tpl_dir.'main.tpl');
$this->tpl->copy_template = "{$forum_ajax}<script type='text/javascript' src='{$config['http_home_url']}engine/forum/ajax/dle_forum.js'></script>\r\n".$this->tpl->copy_template;
$this->tpl->set('{BOARD}',$this->tpl->result['dle_forum']);
$this->tpl->set('',$forum_content);
if ($is_logged)
{
$this->tpl->set('[profile]','');
$this->tpl->set('[/profile]','');
}
else
{
$this->tpl->set_block("'\\[profile\\](.*?)\\[/profile\\]'si",'');
}
$copyrigt = false;
$time_hash    = $fcache->get('time_hash');
$is_time_hash = md5(__FILE__.date('d').'Oce4Hon6Idh2');
if ($forum_config['key'] &&$forum_config['copyright'] ||$time_hash != $is_time_hash)
{
$is_date_hash = md5(__FILE__.date('d').'neKP5gFD4haY');
$date_hash = $fcache->get('date_hash');
if ($time_hash != $is_time_hash) {$date_hash = '';}
if ($is_date_hash == $date_hash) {$copyrigt = true;}
else
{
require_once ENGINE_DIR.'/forum/sources/components/include/cp.php';
$CP = new CP;
if ($CP->check_key('activate')) {$fcache->set('date_hash',$is_date_hash);$copyrigt = true;}
if ($CP->check_key($forum_config['sn'])) {$fcache->set('time_hash',$is_time_hash);}else {die('Error: activate your copy DLE Forum in the ACP.');}
}
if ($copyrigt and $forum_config['key_name']) {$copyrigt = $f_lang['reg_name'].$forum_config['key_name'];}
}
if (!$copyrigt)
{
$this->tpl->copy_template = $this->tpl->copy_template."<div class=\"copyright\" style=\"display:block;\"><a href=\"{$a_forum_url}copyright=show\">DLE Forum</a> v.".$forum_config['version_id'].' &copy; '.date('Y')." <a href=\"http://dlekey.cn\" title=\"Форум для DataLife Engine\">DLE Files Group</a></div>".$copyrigt;
$powered_by = ' (Powered By DLE Forum)';
if ($_REQUEST['act'] == 'copyright'||$_REQUEST['copyright'] ||$_GET['copyright'])
{
$copyright_content  = @file_get_contents('http://dlekey.cn/extras/dle-forum.odf');
$this->tpl->copy_template = $copyright_content;
}
}
if (!$metatags['title'] &&count($forum_bar_array) >1 &&$app = $forum_bar_array[count($forum_bar_array)-1])
{
$metatags['title'] = $forum_config['forum_title'] .' &raquo; '.$app;
}
if (!$metatags['title'])
{
$metatags['title'] = $forum_config['forum_title'] .$powered_by;
}
$tpl->compile('content');
$tpl->clear();
}
}
?>