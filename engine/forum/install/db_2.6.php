<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: db_2.6.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DLE_FORUM_INSTALL'))
{
  die("Hacking attempt!");
}

	$db->query("ALTER TABLE " . PREFIX . "_forum_forums ADD `is_category` TINYINT( 1 ) NOT NULL");
    
    $query = $db->query("SELECT * FROM " . PREFIX . "_forum_category");
    
    $posi = 0;
    
    while ($row = $db->get_row($query))
    {
        $posi++;
        
        $db->query("INSERT INTO " . PREFIX . "_forum_forums (parentid, name, position, is_category) values ('-1', '{$row['cat_name']}', '$posi', '1')");
        
        $parent_id = $db->insert_id();
        
        $db->query("UPDATE " . PREFIX . "_forum_forums SET parentid = '$parent_id' WHERE main_id = '{$row['sid']}'");
    }
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `redirect` VARCHAR( 250 ) NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums ADD `alt_name` VARCHAR( 50 ) NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_topics ADD `alt_name` VARCHAR( 200 ) NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_users ADD `forum_read` VARCHAR( 20 ) NOT NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_moderators ADD `banned` TINYINT( 1 ) NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_moderators ADD `read_mode` TINYINT( 1 ) NULL";
    
    $db_query[] = "ALTER TABLE " . PREFIX . "_forum_forums DROP `main_id`";
    
    $db_query[] = "DROP TABLE " . PREFIX . "_forum_category";
	
?>