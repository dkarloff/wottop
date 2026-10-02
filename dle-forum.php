<?php
/*
=====================================================
 DLE Forum
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 Copyright (c) 2011 DLE Files Group
=====================================================
*/

define('DLE_FORUM_INSTALL', 1);

error_reporting(E_ALL ^ E_NOTICE);

@session_start();

@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_NOTICE);

define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname (__FILE__));
define('ENGINE_DIR', ROOT_DIR . '/engine');

require_once ENGINE_DIR . '/data/config.php';

@include ENGINE_DIR . '/data/forum_config.php';

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

$config['charset'] = "windows-1251";
$db_charset = "cp1251";
$db_collate = "cp1251_general_ci";

$url = $config['http_home_url'];

$_TIME = time() + ($config['date_adjust'] * 60);

$topic_date = date ("Y-m-d H:i:s", $_TIME);

extract($_REQUEST, EXTR_SKIP);

$act = $_REQUEST['act'];

if (!intval($act))
{
	$act = 1;
	
	$next_act = 2;
}

$button_value = 'Дальше >';

switch ($act)
{	
	default:
	case "1":
	
	if ($forum_config['version_id'] !== '2.6')
	{
		if ($config['version_id'] < '8.5')
        {
            $check_dle_info = "<font color=\"red\"><b>Для правильной работы необходима DataLife Engine v.8.5+.</b></font><br /><br />";
        }
        else { $check_dle_info = ""; }
        
        $content = "Добро пожаловать в мастер установки DLE Forum.<br />Данный мастер поможет вам установить скрипт всего за пару минут. Однако, не смотря на это, мы настоятельно рекомендуем Вам ознакомиться с документацией по работе с форумом, а также по его установке, которая поставляется вместе со скриптом.<br /><br />Прежде чем начать установку убедитесь, что все файлы дистрибутива загружены на сервер, а также выставлены необходимые права доступа для папок и файлов.<br /><br />{$check_dle_info}Приятной Вам работы,<br /><br />DLE Files Group";
	}
	else
	{
		$content = "Внимание!!!<br />На сервере обнаружена уже установленная копия DLE Forum. Если вы хотите еще раз произвести установку, то вам необходимо вручную удалить файл <b>/engine/data/forum_config.php</b>, используя FTP протокол.";
		
		$next_act = 1;
		
		$button_value = 'Обновить >';
	}
	
	break;
	
	case "2":
	
	$licence_file = @file_get_contents('http://dlekey.cn/extras/licence.odf');
    if (!$licence_file) { $licence_file = "<a href='http://dlekey.cn/dle_forum_license.html'>http://dlekey.cn/dle_forum_license.html</a>"; }
	
	$content = "<script language='javascript'>
	check_eula = function(){
	if( document.getElementById( 'eula' ).checked == true ){ return true; }
	else { alert( 'Вы должны принять лицензионное соглашение, прежде чем продолжите установку.' ); return false; }} 
	document.getElementById( 'install' ).onsubmit = check_eula;</script>
	<div class=eula>{$licence_file}</div>
<input type=hidden name=action value=function_check>
<input type='checkbox' name='eula' id='eula'><b>Я принимаю данное соглашение</b>";
	
	$next_act = 3;
	
	break;
	
	case "3":
	
	$next_act = 4;
	
	$important_files = array(
	'./engine/data/',
	'./engine/forum/cache/',
	'./engine/forum/cache/system',
	'./uploads/forum/',
	'./uploads/forum/files/',
	'./uploads/forum/images/',
	'./uploads/forum/thumbs/',
	);
	
	$write_files = array(
	'./engine/engine.php',
	'./engine/inc/options.php',
	);
	
	if ($_SESSION['auto_install'])
	{
		$important_files = array_merge ($important_files, $write_files);
	}
	
	$chmod_errors = 0;
	$not_found_errors = 0;
	
	foreach ($important_files as $file)
	{
		if(!file_exists($file))
		{
			$file_status = "<font color=red>не найден!</font>";
            $not_found_errors ++;
		}
		elseif(is_writable($file))
		{
			$file_status = "<font color=green>разрешено</font>";
		}
		else
		{
			@chmod($file, 0777);
			if(is_writable($file))
			{
				$file_status = "<font color=green>разрешено</font>";
			}
			else
			{
				@chmod("$file", 0755);
				if(is_writable($file))
				{
					$file_status = "<font color=green>разрешено</font>";
				}
				else
				{
					$file_status = "<font color=red>запрещено</font>";
                    $chmod_errors ++;
				}
			}
		}
		
		$chmod_value = @decoct(@fileperms($file)) % 1000;
		
		$content_chmod .= "<tr><td height=22>&nbsp;$file</td>
		                       <td>&nbsp; $chmod_value</td>
							   <td>&nbsp; $file_status</td></tr>";
	}
	
	if ($chmod_errors == 0 and $not_found_errors == 0)
	{
		$status_report = '<br />Проверка успешно завершена! Можете продолжить установку!';
	}
	else
	{
		if ($chmod_errors > 0)
		{
			$status_report = "<font color=red>Внимание!!!</font><br /><br />Во время проверки обнаружены ошибки: <b>$chmod_errors</b>. Запрещена запись в файл.<br />Вы должны выставить для папок CHMOD 777, для файлов CHMOD 666, используя ФТП-клиент.<br /><br /><font color=red><b>Настоятельно не рекомендуется</b></font> продолжать установку, пока не будут произведены изменения.<br />";
		}
		
		if ($not_found_errors > 0)
		{
			$status_report .= "<font color=red>Внимание!!!</font><br />Во время проверки обнаружены ошибки: <b>$not_found_errors</b>. Файлы не найдены!<br /><br /><font color=red><b>Не рекомендуется</b></font> продолжать установку, пока не будут произведены изменения.<br />";
		}
		
		$next_act = 3;
		
		$button_value = 'Обновить >';
	}
	
	$content = "<table border=0 width=100%>".$content_chmod."</table>".$status_report."";
	
	break;
	
	case "4":
	
	if (!$forum_config['version_id'])
	{
		require_once ENGINE_DIR.'/forum/install/db.php';
		
		foreach($db_query as $table)
		{
			$db->query($table, false);
		}
		
		$content = "База данных MySQL создана...<br /><br />Все необходимые поля внесены...<br /><br />Нажмите &quot;Дальше&quot; для продолжения.";
		
		$next_act = 5;
	}
	
	else
	{
		$db_query = array();
        
        if ($forum_config['version_id'] == '2.0')
		{
			require_once ENGINE_DIR.'/forum/install/db_2.1.php';
			
			$version_update = true;
		}
		
		if ($forum_config['version_id'] == '2.1' OR $forum_config['version_id'] < '2.1')
		{
			require_once ENGINE_DIR.'/forum/install/db_2.2.php';
			
			$version_update = true;
		}
		
		if ($forum_config['version_id'] == '2.2'  OR $forum_config['version_id'] < '2.2')
		{
			require_once ENGINE_DIR.'/forum/install/db_2.3.php';
			
			$version_update = true;
		}
		
		if ($forum_config['version_id'] == '2.3' OR $forum_config['version_id'] < '2.3')
		{
			require_once ENGINE_DIR.'/forum/install/db_2.4.php';
			
			$version_update = true;
		}
        
        if ($forum_config['version_id'] == '2.4' OR $forum_config['version_id'] < '2.4')
		{
			require_once ENGINE_DIR.'/forum/install/db_2.5.php';
			
			$version_update = true;
		}
        
        if ($forum_config['version_id'] == '2.5' OR $forum_config['version_id'] < '2.5')
		{
			require_once ENGINE_DIR.'/forum/install/db_2.6.php';
			
			$version_update = true;
		}
		
		
		if ($version_update)
		{
			foreach($db_query as $table)
			{
				$db->query($table, false);
			}
			
			$content = "База данных MySQL обновлена...<br /><br />Все необходимые изменения внесены...<br /><br />Нажмите &quot;Дальше&quot; для продолжения.";
			
			$next_act = 5;
		}
		else
		{
			$content = "Обнаружена ошибка версии.<br /><br />Обновление невозможно.";
			
			$next_act = 4;
			
			$button_value = 'Повторить >';
		}
	}
	
	break;
	
	case "5":
	
	if ($_REQUEST['install_type'])
	{
		if ($_REQUEST['install_type'] == "auto")
		{
			require_once ENGINE_DIR.'/forum/install/write.php';
			
			foreach ($edit_files as $key => $value)
			{
				$important_files[] = $edit_files[$key]['open'];
			}
			
			foreach ($edit_files as $file)
			{
				if (is_writable($file['open']))
				{
					$file_status = "<font color=green>разрешено</font>";
				}
				else
				{
					@chmod($file['open'], 0777);
					
					if(is_writable($file['open']))
					{
						$file_status = "<font color=green>разрешено</font>";
					}
					else
					{
						@chmod($file['open'], 0755);
						
						if(is_writable($file['open']))
						{
							$file_status = "<font color=green>разрешено</font>";
						}
						else
						{
							$file_status = "<font color=red>запрещено</font>";
							$chmod_errors ++;
						}
					}
				}
				
				if (ereg($file['key'], @file_get_contents($file['open'])))
				{
					$file_status = "<font color=red>заменить</font>";
					$file_errors ++;
				}
				
				$chmod_value = @decoct(@fileperms($file['open'])) % 1000;
				
				$content_chmod .= "<tr><td height=22>&nbsp;{$file['open']}</td>
				                   <td>&nbsp; $chmod_value</td>
								   <td>&nbsp; $file_status</td></tr>";
			}
			
			if (!$chmod_errors and !$file_errors)
			{
				foreach ($edit_files as $file)
				{
					$file_edit = file_get_contents($file['open']);
					
					if (!eregi($file['key'], $file_edit))
					{
						$new_file = str_replace($file['find'], $file['before'].(($file['replace']!="")?$file['replace']:$file['find'])."\n".$file['add'], $file_edit);
						
						$fd = @fopen($file['open'], "w+");
						fwrite($fd, $new_file);
						fclose($fd);
						chmod($file['open'], 0644);
					}
				}
				
				$content = "Файлы успешно подключены...";
				
				$next_act = 6;
			}
			
			else
			{
				$error_num = $chmod_errors + $file_errors;
				
				$status_report = "<font color=red>Внимание!!!</font><br /><br />Во время проверки обнаружены ошибки <b>{$error_num}</b>:<br /><br />";
				
				if ($chmod_errors)
				{
					$status_report = $status_report." - Запрещена запись в файлы: {$chmod_errors}. <i>(Вы должны выставить CHMOD)</i><br /><br />";
				}
				
				if ($file_errors)
				{
					$status_report = $status_report." - Измененные файлы: {$file_errors}. <i>(Вы должны заменить файлы на оригинальные)</i><br />";
				}
				
				$content = "<table border=0 width=100%>".$content_chmod."</table>".$status_report."";
				
				$button_value = 'Обновить >';
				
				$next_act = 5;
			}
		}
		
		else
		{
			$content = "Прочитайте <b>readme.html</b>";
			
			$next_act = 6;
		}
	}
	
	else
	{
		$content = "Выберите тип установки:<br /><input type=\"radio\" name=\"install_type\" class=\"radiobutton\" value=\"auto\" checked=\"checked\">Автоматический<br /><input type=\"radio\" name=\"install_type\" class=\"radiobutton\" value=\"no\" />Ручной";
		
		$next_act = 5;
	}
	
	break;
	
	case "6":
	
	$config_file = fopen("engine/data/forum_config.php", "w+");
	
	require_once ENGINE_DIR.'/forum/install/forum_config.php';
	
	if ($config_file and $forum_config)
	{
		fwrite($config_file, $forum_config);
		fclose($config_file);
		@chmod("engine/data/forum_config.php", 0666);
		
		$content = "Поздравляем Вас, DLE Forum был успешно установлен.<br />Вы можете просмотреть теперь главную <a href=\"index.php?do=forum\">страницу вашего форума</a>  и посмотреть возможности скрипта. Либо Вы можете <a href=\"admin.php?mod=forum\">зайти</a> в панель управления DLE Forum и изменить настройки системы.<br /><br /><font color=\"red\">Внимание: удалите файл <b>dle-forum.php</b> во избежание повторной установки скрипта!</font><br /><br />Приятной Вам работы<br /><br />DLE Files Group";
		
		$button_value = 'Готово';
		
		$next_act = 7;
	}
	else
	{
		$content = "Извините, но невозможно создать файл <b>.engine/data/forum_config.php</b>.<br />Проверьте правильность проставленного CHMOD!";
		
		$button_value = 'Обновить >';
		
		$next_act = 6;
	}
	
	break;
	
	case "7":
	
	require_once ENGINE_DIR.'/forum/classes/cache.php';
	
	$fcache = new forum_cache;
	
	$fcache->delete();
	
	$fcache->clear();
	
	if ($config['allow_alt_url'] == "yes")
	{
		$forum_link = $config['http_home_url'].'forum/';
	}
	else
	{
		$forum_link = $config['http_home_url'].'?do=forum';
	}
	
	header("Location: $forum_link");
	
	break;
}

$progress_array = array (
'1' => "Требования",
'2' => "Cоглашение",
'3' => "CHMOD",
'4' => "База данных",
'5' => "Установка",
'6' => "Завершение",
);

foreach ($progress_array as $prog => $name)
{
	if ($prog == $act) $step_class = 'step_doing';
	
	elseif ($prog < $act) $step_class = 'step_done';
	
	else $step_class = 'step_notdone';
	
	if ($act == '6') $step_class = 'step_done';
	
	$progress .= "<li class='{$step_class}'>{$name}</li>";
}

$install_tpl = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>DLE Forum</title>
<link href="/engine/forum/install/style.css" rel="stylesheet" type="text/css" />
</head>
{$js}
<body>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		
		<div id='ipswrapper'>
		<form id="install" method="post" action="$PHP_SELF">
		    <div class='main_shell'>

		 	    <h1><img src='/engine/forum/install/images/package_icon.gif' align='absmiddle' /> Установщик продуктов DLE Files Group</h1>
		 	    <div class='content_shell'>
		 	        <div class='package'>
		 	            <div>
		 	                <div class='install_info'>
		 	                    <h3>Требования</h3>
		 	                    		 	                    
    		 	                <ul id='progress'>{$progress}</ul>
    		 	            </div>
		 	            
    		 	            <div class='content_wrap'>
    		 	                <div style="padding-bottom:4px; border-bottom-width:1px; border-bottom-color:rgb(147,147,147); border-bottom-style:solid;">
   		 	                     <div style="vertical-align:middle;">
									 <h2>DLE Forum</h2><br /><strong>Version 2.6</strong></div>
							</div>
   		 	                  <div style="clear:both;"></div>
								  <div>
								  <div><br />{$content}</div>
								  </div></div>
								  </div>
		 	            <br clear='all' />
    
		 	            <div class='hr'></div>
		 	            <div style="padding-top:17px; padding-right:15px; padding-left:15px;">
		 	                <div style="float:left;">
		 	                    <input type='button' class='nav_button' value='Прекратить установку' onclick="window.location='?';return false;" />
		 	                </div>

		 	                <div style="float:right;">
							 <input type=hidden name="act" value="{$next_act}">
							 <input type='submit' id='button' class='nav_button' value='{$button_value}'>	</div>
		 	            </div>
		 	            <div style="clear:both;"></div>
		 	            <div class='copyright'>&copy; 2011 DLE Files Group</div>
		 	        </div>

		 	    </div>
    		</div>
    		</form>
    	</div>
	
	</body>
</html>
HTML;

echo $install_tpl;

?>