<div style="margin-top:1px;">
  <div class="borderwrap">
    <div class="maintitle">
<img src="{THEME}/forum/images/nav_m.gif" width="8" height="8" border="0" alt="" />&nbsp;Редактирование темы</div>
<table width="100%" cellspacing="1" cellpadding="0" class="ipbtable">
    <tr>
      <th style='text-align: left' colspan='2'><strong>Тема</strong></th>
    </tr>
                    <tr>
                      <td class='row2' width="120" height="25">Название темы:</td>
                      <td class='row2' width="340"><input type="text" name="topic_title" value="{topic_title}" maxlength="150" class="forum_input" /></td>
                    </tr>
                    <tr>
                      <td class='row2' width="120" height="25">Описание темы:</td>
                      <td class='row2' width="340"><input type="text" name="topic_descr" value="{topic_descr}" maxlength="150" class="forum_input" /> (Опционально)</td>
                    </tr>
<tr>
<td class='row2' width="120" height="25" style="border-right: 1px solid #c2c1c1; text-align: right;"><strong>Описание для темы:</strong></td>
<td class='row2' width="340"><input type="text" name="topic_meta_descr" value="{topic_meta_descr}" maxlength="200" class="forum_input" /> (не более 200 символов)</td>
</tr>
<tr>
<td class='row2' width="120" height="25" style="border-right: 1px solid #c2c1c1; text-align: right;"><strong>Ключевые слова:</strong></td>
<td class='row2' width="340"><input type="text" name="topic_meta_keywords" value="{topic_meta_keywords}" maxlength="500" class="forum_input" /></td>
</tr>

[poll]
					    <tr>
      <th style='text-align: left' colspan='2'><strong>Опрос</strong></th>
    </tr>
[poll]
<tr>
 	<td class='row1' style='width: 20%; text-align: right'>
		<strong>Опции опроса</strong>
 	</td>
	<td class='row2'>

					  <a href="JavaScript:ShowHide('poll');">Нажмите сюда для управления опросом в этой теме</a><br />
					  <div style='display:none' id='poll'>

                        <table cellpadding="0" cellspacing="0" width="460">
                          <tr>
                            <td width="120"><img src="{THEME}/forum/images/spacer.gif" width="120" height="1" border="0" alt="" /></td>
                            <td width="340"><img src="{THEME}/forum/images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
                          </tr>
                          <tr>
                            <td width="120" height="25">Заголовок Опроса:</td>
                            <td width="340"><input type="text" name="vote_title" class="forum_input" / value="{vote_title}"></td>
                          </tr>
                          <tr>
                            <td width="120" height="25">Вопрос:</td>
                            <td width="340"><input type="text" name="frage" class="forum_input" / value="{frage}"></td>
                          </tr>
                          <tr>
                            <td colspan="2">Варианты ответов: (Каждая новая строка является новым вариантом ответа)<br /><textarea name="vote_body" class="forum_textarea">{vote_body}</textarea><br /></td>
                        </tr>
						<tr>
						<td colspan="2"><br /><input type="checkbox" value="1" name="poll_multiple">  Разрешить выбор нескольких вариантов</td>
						</tr>
                        </table>
					  </div>
					  </td>
                    </tr>
[/poll]
                        <tr>
      <th style='text-align: left' colspan='2'><strong>Опции</strong></th>
    </tr>
<tr>
 	<td class='row1' style='width: 20%; text-align: right'>
		<strong>Иконки сообщения</strong>
 	</td>
	<td class='row2'>
	{post_icons}	
	</td>
</tr>
                    <tr>
                      <td style='text-align: center' colspan='2' class='row2'><br /><input name="submit" type="submit" class="button" value="Отредактировать тему" /></td>
                    </tr>
    </table>
  </div>
</div>