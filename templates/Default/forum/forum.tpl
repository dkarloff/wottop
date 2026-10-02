{banner}
[rules]
<div class="borderwrap">
  <div class="maintitle"> <img src="{THEME}/forum/images/nav_m.gif" width="8" height="8" border="0" alt="" />&nbsp;{rules-name}</div>
  <table width="100%" cellspacing="1" cellpadding="0" class="ipbtable">
    <tr>
      <td align="left" class="row2">{rules-text}</td>
    </tr>
    <tr>
      <td class="catend"><!-- должно быть пусто --></td>
    </tr>
  </table>
</div>
<br />
[/rules]
{subforums}
<div style="padding-top:0px; padding-right:0px; padding-bottom:5px; padding-left:0px;" align="right">[new_topic]<img src="{THEME}/forum/images/t_new.gif" border="0" alt="" />[/new_topic]</div>
<div class="borderwrap">
  <div class="maintitle">
    <table width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="99%"><div><img src="{THEME}/forum/images/nav_m.gif" width="8" height="8" border="0" alt="" />&nbsp;{forum}</div></td>
        <td width="1%" align="right" nowrap="nowrap"><div class="popmenubutton">[options]Опции форума <img src="{THEME}/forum/images/dlet_action_down.gif" border="0" alt="Открыть меню" />[/options]</div></td>
      </tr>
    </table>
  </div>
  <table width="100%" cellspacing="1" cellpadding="0" class="ipbtable">
    <tr>
      <th>&nbsp;</th>
	  <th>&nbsp;</th>
      <th width="50%" align="left">Название темы</th>
      <th width="7%" align="center">Ответов</th>
      <th width="14%" align="center">Автор</th>
      <th width="7%" align="center">Просмотров</th>
      <th width="22%" align="left" nowrap="nowrap">Последнее сообщение</th>
[selected]<th align="center">&nbsp;</th>[/selected]
    </tr>
{topics}
    <tr>
      <td colspan="8" class="row2">{info}</td>
    </tr>
	<tr>
      <td colspan="8" class="row1"><div style="float:left;">[fast-search]<input type="text" name="search_text"/">&nbsp;<input name="submit" type="submit" class="button" value=">>>"/>[/fast-search]</div><div align="right">[moderation]{moderation}&nbsp;<input name="gomod" type="submit" class="button" value="Ok"/>[/moderation]</div></td>
    </tr>
    <tr>
      <td colspan="8" class="catend"><!-- должно быть пусто --></td>
    </tr>
  </table>
</div>
<br />
{navigation}
[online]
<div class="borderwrap" style="padding:1px;">
  <div class="formsubtitle" style="padding:5px;"><strong>{all_count}</strong>чел. читают этот форум (гостей: {guest_count})</div>
  <div class="row1" style="padding:5px;">Пользователей: <strong>{member_count}</strong> {member_list}</div>
</div>
<br />
[/online]

<div class="activeusers">
  <div class="row2">
    <table width="100%" cellspacing="1" cellpadding="0" class="ipbtable">
      <tr>
        <td width="240" valign="top" class="row2"><img src="{THEME}/forum/images/dlet_norm.gif" width="19" height="15" border="0" alt="Открытая тема (есть новые ответы)" />&nbsp;&nbsp;Открытая тема (есть новые ответы)<br />
          <img src="{THEME}/forum/images/dlet_norm_no.gif" width="19" height="15" border="0" alt="Открытая тема (нет новых ответов)" />&nbsp;&nbsp;Открытая тема (нет новых ответов)<br />
		  <img src="{THEME}/forum/images/dlet_hot.gif" width="19" height="15" border="0" alt="Горячая тема (есть новые ответы)" />&nbsp;&nbsp;Горячая тема (есть новые ответы)<br />
          <img src="{THEME}/forum/images/dlet_hot_no.gif" width="19" height="15" border="0" alt="Горячая тема (нет новых ответов)" />&nbsp;&nbsp;Горячая тема (нет новых ответов)</td>
        <td valign="top" class="row2"><img src="{THEME}/forum/images/dlet_poll.gif" width="19" height="15" border="0" alt="Опрос (есть новые голоса)" />&nbsp;&nbsp;Опрос (есть новые голоса)<br />
          <img src="{THEME}/forum/images/dlet_poll_no.gif" width="19" height="15" border="0" alt="Опрос (нет новых голосов)" />&nbsp;&nbsp;Опрос (нет новых голосов)<br />
          <img src="{THEME}/forum/images/dlet_closed.gif" width="14" height="14" border="0" alt="Закрытая тема" />&nbsp;&nbsp;&nbsp;Закрытая тема</td>
      </tr>
    </table>
  </div>
</div>