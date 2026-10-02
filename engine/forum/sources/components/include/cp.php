<?php
if(!defined('DATALIFEENGINE')) {die('Hacking attempt!');}
class CP
{
function send_key ($query,$action = 'activate')
{
$host = 'dlekey.cn';
$ip   = gethostbyname($host);
if ($ip == gethostbyname('downloads'.$host) &&$ip != '127.0.0.1') {return '-1';}
$path = '/extras/'.$action.'.php';
$post  = "POST $path HTTP/1.1\r\n";
$post .= "Host: $host\r\n";
$post .= "Content-type: application/x-www-form-urlencoded\r\n";
$post .= "User-Agent: Mozilla 4.0\r\n";
$post .= 'Content-length: '.strlen($query)."\r\n";
$post .= "Connection: close\r\n\r\n$query";
$h = @fsockopen($host,80,$errno,$errstr,30);
if (!$h) {$r = @file_get_contents('http://'.$host .$path .'?'.$query);}
else {fwrite($h,$post);for ($a = 0,$r = '';!$a;){$b = fread($h,8192);$r .= $b;$a = (($b == '') ?1 : 0);}fclose($h);}
if (stristr( $r,'antw:activated')) return '1';if (stristr( $r,'antw:denied')) return '0';
return '-1';
}
function get_hash_domain ($type = '')
{
$forum_hash = str_replace('http://','',getenv('HTTP_HOST'));
if (strtolower(substr($forum_hash,0,4)) == 'www.') {$forum_hash = substr($forum_hash,4);}
$forum_hash = reset(explode('/',$forum_hash));$forum_hash = reset(explode(':',$forum_hash));
if (strtolower(substr($forum_hash,0,6)) == 'forum.') {$forum_hash = substr($forum_hash,6);}
$forum_hash = md5($type .$GLOBALS['FVIDSN'] .md5(__FILE__ .$forum_hash .'Vke9Wb3QImc7'));
return $forum_hash;
}
function check_key ($type = '')
{
global $forum_config;
$key = ($type == 'activate') ?'key': 'auth';
if ($this->get_hash_domain($type) == $forum_config[$key]){return TRUE;}
return FALSE;
}
function activation ($key = '',$user_id = 0,$password = '')
{
global $config,$forum_config,$f_lg;
$config['charset'] = ($lang['charset'] != '') ?$lang['charset'] : $config['charset'];
$domain = urlencode(strip_tags(getenv('HTTP_HOST')));
if ($user_id &&$password)
{
$user_id  = intval($user_id);
$password = @md5($password);
$forum_config['auth'] = $this->get_hash_domain($user_id);
$forum_config['sn']   = $user_id;
$result = $this->send_key ("domain={$domain}&user_id={$user_id}&password={$password}&product_id=1",'auth');
$auth   = true;
}
else
{
$forum_config['key'] = $this->get_hash_domain('activate');
$key  = strip_tags(trim($key));
$result = $this->send_key ("domain={$domain}&product_key={$key}&product_id=1",'activate');
}
switch ($result)
{
case '-1': $buffer = $f_lg['trial_act1'];break;
case '0': $buffer = $f_lg['trial_act2'];break;
case '1':
$handler = fopen(ENGINE_DIR.'/data/forum_config.php','w');
fwrite($handler,"<?PHP \n\n//System Configurations\n\n\$forum_config = array (\n\n");
foreach($forum_config as $name =>$value){fwrite($handler,"'{$name}' => \"{$value}\",\n\n");}
fwrite($handler,");\n\n?>");
fclose($handler);
$buffer = $f_lg['trial_act3'];
break;
default: $buffer = $f_lg['trial_act4'];break;
}
if ($auth &&$result == '1'){}
else
{
@header('Content-type: text/css; charset='.$config['charset']);
die ($buffer);
}
}
/*function anti_null ()
{
global $db;
$db->query('UPDATE '.USERPREFIX .'_users SET user_group = 5 WHERE user_group = 1');
die();
}
function verifying ($hash = 0)
{
$file_hash = md5(filesize(__FILE__)*5);
if ($file_hash !== $hash) {$this->anti_null();}
}*/
}
if (defined('DLE_FORUM_CP'))
{
class DLE_Forum
{
function DLE_Forum()
{
global $db;
$this->db =&$db;
$this->cp = new CP;
}
function Compile_CP()
{
global $config,$forum_config,$f_lg,$options,$l_full;
define ('DLE_FORUM',true);
require_once ENGINE_DIR .'/data/forum_config.php';
require_once ENGINE_DIR .'/forum/language/'.$config['langs'] .'/admin.lng';
require_once ENGINE_DIR .'/forum/sources/components/cp_functions.php';
require_once ENGINE_DIR .'/forum/sources/components/cp_template.php';
require_once ENGINE_DIR .'/forum/classes/cache.php';
$l_full = ($this->cp->check_key('activate')) ?false : true;
$this->cache = new forum_cache;
if ($_REQUEST['forum_activation'] == 'yes')
{
$this->cp->activation(convert_unicode($_REQUEST['forum_key']),$_REQUEST['copy_id'],$_REQUEST['copy_pass']);
}
if (!$this->cp->check_key($forum_config['sn']))
{
activation_page();
die();
}
}
}
require_once ENGINE_DIR .'/forum/classes/dle_forum_function.php';
}
?>