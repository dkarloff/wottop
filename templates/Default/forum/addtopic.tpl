<span id="post-preview"></span>
<div style="margin-top:1px;">
  <div class="borderwrap">
    <div class="maintitle"> 
<img src="{THEME}/forum/images/nav_m.gif" width="8" height="8" border="0" alt="" />&nbsp; Создание новой темы</div>
<table width="100%" cellspacing="1" cellpadding="0" class="ipbtable">
[not-logged]
                    <tr>
                      <td width="120" height="25">Ваше Имя:</td>
                      <td width="340"><input type="text" name="name" class="forum_input" /></td>
                    </tr>
                    <tr>
                      <td width="120" height="25">Ваш E-Mail:</td>
                      <td width="340"><input type="text" name="mail" class="forum_input" /></td>
                    </tr>
[/not-logged]

    <tr>
      <th style='text-align: left' colspan='2'><strong>Тема</strong></th>
    </tr>
<tr>
 	<td class='row1' style='width: 20%; text-align: right'>
		<strong>Название темы</strong>
 	</td>
	<td class='row2'>
		<input type="text" name="topic_title" value="{topic_title}" size="50" maxlength="200" class="forum_input" />
	</td>
</tr>
<tr>
 	<td class='row1' style='width: 20%; text-align: right'>
		<strong>Описание темы</strong>
 	</td>
	<td class='row2'>
		<input type="text" name="topic_descr" value="{topic_descr}" size="50" maxlength="200" class="forum_input" />
	</td>
</tr>


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
                            <td width="340"><input type="text" name="vote_title" class="forum_input" /></td>
                          </tr>
                          <tr>
                            <td width="120" height="25">Вопрос:</td>
                            <td width="340"><input type="text" name="frage" class="forum_input" /></td>
                          </tr>
                          <tr>
                            <td colspan="2">Варианты ответов: (Каждая новая строка является новым вариантом ответа)<br /><textarea name="vote_body" class="forum_textarea"></textarea><br /></td>
                        </tr>
						<tr>
						<td colspan="2"><br /><input type="checkbox" value="1" name="poll_multiple">  Разрешить выбор нескольких вариантов</td>
						</tr>
                        </table>
					  </div>
	</td>
</tr>
[/poll]
[not-wysywyg]

<tr>
      <th style='text-align: left' colspan='2'><strong>Сообщение</strong></th>
    </tr>
<tr>
	<td class='row1' style='width: 20%;'>&nbsp;</td>
 	<td class='row1'>
{bbcode}
 	</td>
</tr>
[/not-wysywyg]
<tr>
			<td class='row1' style='width: 20%;'>&nbsp;</td>
			<td class='row1'>[not-wysywyg]<textarea id="post_text" name="post_text" class="forum_textarea">{text}</textarea>[/not-wysywyg]{wysiwyg}<br /></td>
</tr>
[sec_code]
    <tr>
      <th style='text-align: left' colspan='2'><strong>Защитный код</strong></th>
    </tr>
					<tr>
 	<td class='row1' style='width: 20%; text-align: right'>
		<strong>Код</strong>
 	</td>
	<td class='row2'>
	{sec_code}	
	</td>
                    </tr>
					<tr>
 	<td class='row1' style='width: 20%; text-align: right'>
		<strong>Введите код</strong>
 	</td>
                      <td class='row2'><input type="text" name="sec_code" maxlength="150" style="width:115px" class="forum_input" /></td>
                    </tr>
[/sec_code]

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
<td class='row2' style='width: 20%;'></td>
<td class='row2'><input type="checkbox" value="0" name="subscription" /> Подписаться на тему</td>
</tr>


                    <tr>
                      <td class='row1' style='text-align: center' colspan="2"><br /><input name="submit" type="submit" class="button" value="Создать тему" /> &nbsp;<input type="button" class="button" onclick="PostPreview();" value="Предварительный просмотр" /></td>
                    </tr>
    </table>
  </div>
</div>