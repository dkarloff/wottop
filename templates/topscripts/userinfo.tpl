


<div class="dpad">
 <h2 class="comen">Пользователь: {usertitle}</h2>
  <div class="text nopadd">
    <ul class="userinfo">
      <li><img src="{foto}" width="100" height="100" alt="{usertitle}" style="float:right;"/></li>
    <li>Полное имя: {fullname}</li>
    <li>Дата регистрации: {registration}</li>
    <li>Последнее посещение: {lastdate}</li>
    <li>Группа: {status} [time_limit]&nbsp;В группе до: {time_limit}[/time_limit] </li>
    <li>Место жительства: {land}</li>
    <li>Номер ICQ: {icq}</li>
    <li>Немного о себе: <p>{info}</p></li>
     <li>Количество публикаций: {news-num} [{news}] </li>
    <li>Количество комментариев: {comm-num} [{comments}]</li> 
      <li>[{email}] [not-group=5][{pm}][/not-group]</li>
  </ul>
  </div> 
  <span class="small">{edituser}</span>
      <div class="clr"></div> 
  </div>
[not-logged]
<div id="options" style="display:none;">
 <fieldset class="fieldse"> 
   <legend> Редактирование профиля </legend> 
      <table class="tableform">
			<tr>
				<td class="label">Ваше Имя:</td>
				<td><input type="text" name="fullname" value="{fullname}" class="f_input" /></td>
			</tr>
			<tr>
				<td class="label">Ваш E-Mail:</td>
				<td><input type="text" name="email" value="{editmail}" class="f_input" /><br />
				<div class="checkbox">{hidemail}</div>
				<div class="checkbox"><input type="checkbox" id="subscribe" name="subscribe" value="1" /> <label for="subscribe">Отписаться от подписанных новостей</label></div></td>
			</tr>
			<tr>
				<td class="label">Место жительства:</td>
				<td><input type="text" name="land" value="{land}" class="f_input" /></td>
			</tr>
			<tr>
				<td class="label">Список игнорируемых пользователей:</td>
				<td>{ignore-list}</td>
			</tr>
			<tr>
				<td class="label">Номер ICQ:</td>
				<td><input type="text" name="icq" value="{icq}" class="f_input" /></td>
			</tr>
			<tr>
				<td class="label">Старый пароль:</td>
				<td><input type="password" name="altpass" class="f_input" /></td>
			</tr>
			<tr>
				<td class="label">Новый пароль:</td>
				<td><input type="password" name="password1" class="f_input" /></td>
			</tr>
			<tr>
				<td class="label">Повторите:</td>
				<td><input type="password" name="password2" class="f_input" /></td>
			</tr>
			<tr>
				<td class="label" valign="top">Блокировка по IP:<br />Ваш IP: {ip}</td>
				<td>
				<div><textarea name="allowed_ip" style="width:98%;" rows="5" class="f_textarea">{allowed-ip}</textarea></div>
				<div>
					<span class="small" style="color:red;">
					* Внимание! Будьте бдительны при изменении данной настройки.
					Доступ к Вашему аккаунту будет доступен только с того IP-адреса или подсети, который Вы укажете.
					Вы можете указать несколько IP адресов, по одному адресу на каждую строчку.
					<br />
					Пример: 192.48.25.71 или 129.42.*.*</span>
				</div>
				</td>
			</tr>
			<tr>
				<td class="label">Аватар:</td>
				<td>
				<input type="file" name="image" class="f_input" /><br />
				<div class="checkbox"><input type="checkbox" name="del_foto" id="del_foto" value="yes" /> <label for="del_foto">Удалить фотографию</label></div>
				</td>
			</tr>
			<tr>
				<td class="label">О себе:</td>
				<td><textarea name="info" style="width:98%;" rows="5" class="f_textarea">{editinfo}</textarea></td>
			</tr>
			<tr>
				<td class="label">Подпись:</td>
				<td><textarea name="signature" style="width:98%;" rows="5" class="f_textarea">{editsignature}</textarea></td>
			</tr>
			{xfields}
		</table>
      <div class="fieldsubmit">
	  <input class="fbutton" type="submit" name="submit" value="Отправить" />
			<input name="submit" type="hidden" id="submit" value="submit" />
      </div>

</fieldset></div><br> [/not-logged]