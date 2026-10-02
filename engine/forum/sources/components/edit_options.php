<?php
/*
=====================================================
 DLE Forum - by DLE Files Group
-----------------------------------------------------
 http://dlekey.cn/
-----------------------------------------------------
 File: edit_options.php
=====================================================
 Copyright (c) 2007,2011 DLE Files Group
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

	if ($check_moderator)
	{
		if (moderator_value('edit_topic', $forum_id, $m_member))
		{
			$topic_option .= "<option value=\"04\"> - Редактировать заголовок</option>";
		}
		
		if (moderator_value('move_topic', $forum_id, $m_member))
		{
			$topic_option .= "<option value=\"05\"> - Переместить тему</option>";
		}
		
		if (moderator_value('close_topic', $forum_id, $m_member) and !$row_topic['topic_status'])
		{
			$topic_option .= "<option value=\"02\"> - Закрыть тему</option>";
		}
		
		if (moderator_value('open_topic', $forum_id, $m_member) and $row_topic['topic_status'])
		{
			$topic_option .= "<option value=\"01\"> - Открыть тему</option>";
		}
		
		if (moderator_value('pin_topic', $forum_id, $m_member) and $row_topic['fixed'])
		{
			$topic_option .= "<option value=\"08\"> - Закрепить тему</option>";
		}
		
		if (moderator_value('unpin_topic', $forum_id, $m_member) and !$row_topic['fixed'])
		{
			$topic_option .= "<option value=\"09\"> - Открепить тему</option>";
		}
		
		if (!$row_topic['hidden'])
		{
			$topic_option .= "<option value=\"06\"> - Скрыть тему</option>";
		}
		else
		{
			$topic_option .= "<option value=\"07\"> - Опубликовать тему</option>";
		}
		
		if (moderator_value('delete_topic', $forum_id, $m_member))
		{
			$topic_option .= "<option value=\"03\"> - Удалить тему</option>";
		}
		
		if($topic_option)
		{
			$topic_option = "<option value=\"-1\">Опции модератора</option>".$topic_option;
		}
		
		$posts_option = "<option value=\"00\">Сообщения</option>";
		
		if (moderator_value('combining_post', $forum_id, $m_member))
        {
            $posts_option .= "<option value=\"07\"> - Объединить сообщения</option>";
        }
		
        if (moderator_value('move_post', $forum_id, $m_member))
        {
            $posts_option .= "<option value=\"08\"> - Переместить сообщения</option>";
        }
		
		$posts_option .= "<option value=\"05\"> - Опубликовать сообщения</option>";
		$posts_option .= "<option value=\"06\"> - Скрыть сообщения</option>";
		
		if (moderator_value('mass_prune', $forum_id, $m_member))
		{
			$posts_option .= "<option value=\"04\"> - Удалить сообщения</option>";
		}
	}
	else
	{
		if ($forum_groups[$member_id['user_group']]['topic_edit'] AND $member_id['name'] == $row_topic['author_topic'])
		{
			$topic_option .= "<option value=\"04\"> - Редактировать заголовок</option>";
		}
		
		if ($forum_groups[$member_id['user_group']]['topic_set'] AND $member_id['name'] == $row_topic['author_topic'])
		{
			if ($row_topic['topic_status'])
			{
				$topic_option .= "<option value=\"01\"> - Открыть тему</option>";
			}
			else
			{
				$topic_option .= "<option value=\"02\"> - Закрыть тему</option>";
			}
		}
		
		if ($forum_groups[$member_id['user_group']]['topic_del'] AND $member_id['name'] == $row_topic['author_topic'])
		{
			$topic_option .= "<option value=\"03\"> - Удалить тему</option>";
		}
		
		if ($topic_option)
		{
			$topic_option = "<option value=\"-1\">Опции модератора</option>".$topic_option;
		}
	}
	
?>