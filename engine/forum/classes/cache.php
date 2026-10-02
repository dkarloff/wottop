<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: cache.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
	
class forum_cache
{
	function set($file, $data)
	{
		$fp = fopen(ENGINE_DIR.'/forum/cache/system/'.$file.'.php', 'wb+');
		
		if (!$data) $data = 'a:0:{}';
		
		fwrite($fp, serialize($data));
		
		fclose($fp);
		
		@chmod(ENGINE_DIR.'/forum/cache/system/'.$file.'.php', 0666);
	}
	
	function get($file)
	{
		return unserialize(@file_get_contents(ENGINE_DIR.'/forum/cache/system/'.$file.'.php'));
	}
	
	function delete($cache_area = false)
	{
		$fdir = opendir(ENGINE_DIR.'/forum/cache/system/');
		
		while ($file = readdir($fdir))
		{
			if ($file != '.' and $file != '..' and $file != '.htaccess' and $file != 'online.php')
			{
				if ($cache_area)
				{
					if (strpos($file, $cache_area) !== false) @unlink(ENGINE_DIR.'/forum/cache/system/'.$file);
				}
				else
				{
					@unlink(ENGINE_DIR.'/forum/cache/system/'.$file);
				}
			}
		}
	}
	
	function create($name, $data, $dir=false, $member_prefix=false)
	{
		if ($dir)
		{
			$dir = $dir.'/';
		}
		
		if ($member_prefix)
		{
			$member_prefix = '_'.$member_prefix;
		}
		
		$filename = ENGINE_DIR."/forum/cache/{$dir}".$name.$member_prefix.".tmp";
		
		$fp = fopen($filename, 'wb+');
		
		fwrite($fp, $data);
		
		fclose($fp);
		
		@chmod($filename, 0666);
	}
	
	function cache($name, $dir=false, $member_prefix=false)
	{
		if ($dir)
		{
			$dir = $dir.'/';
		}
		
		if ($member_prefix)
		{
			$member_prefix = '_'.$member_prefix;
		}
		
		$filename = ENGINE_DIR."/forum/cache/{$dir}".$name.$member_prefix.".tmp";
		
        if (file_exists($filename))
        {
            $cache = @file_get_contents($filename);
            
            if (!$cache) return true;
            
            return $cache;
        }
        
		return false;
	}
	
	function clear($name=false, $dir=false)
	{
		if ($dir)
		{
			$dir = '/'.$dir;
		}
		
		$fdir = opendir(ENGINE_DIR.'/forum/cache'.$dir);
		
		while ($file = readdir($fdir))
		{
			if ($file != '.' and $file != '..' and $file != '.htaccess' and
			    $file != 'system' and $file != 'main' and $file != 'forum' and $file != 'topic')
			{
				if ($name)
				{
					if (strpos($file, $cache_area) !== false) @unlink(ENGINE_DIR.'/forum/cache/'.$file);
				}
				else
				{
					@unlink(ENGINE_DIR.'/forum/cache/'.$file);
				}
			}
		}
		
		
	}
}

define('DLE_FORUM_CACHE', true);

$FVIDSN = '1302258644';
	
?>