<?

if ($is_logged == TRUE){



$login_panel = <<<HTML

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td align="left" width="20%">

Вы вошли как: <strong>{$member_id['name']}</strong> ( <a onclick="javascript: showBusyLayer()" href="{$link_logout}">Выход</a> ) &nbsp;

    </td>

HTML;



if ($user_group[$member_id['user_group']]['allow_admin']) {

$login_panel .= <<<HTML

    <td align="right" width="80%">

&nbsp;<a href="{$adminlink}" target="_blank">Админцентр</a> | 

HTML;

}

$login_panel .= <<<HTML

&nbsp;<a href="{$link_profile}"><strong>Профиль</strong></a>&nbsp; | &nbsp;<a href="{$link_pm}">Сообщения ({$member_id['pm_unread']} | {$member_id['pm_all']})</a>

    </td>

  </td>

</table>

HTML;



} else {

$login_panel = <<<HTML

<form method="post" onsubmit="javascript:showBusyLayer()" action=''>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">

    <tr>

      <td align="left">

        <a href="{$link_regist}"><b>Регистрация</b></a> | <a href="{$link_lost}">Напомнить пароль?</a>

      </td>

      <td width="120" align="left" valign="top">

        <input name="login_name" type="text" class="forum_input" style="width: 110px" value="Имя пользователя" onfocus="this.value = '';" />

      </td>

      <td width="120" align="left" valign="top">

        <input name="login_password" type="password" class="forum_input" style="width: 110px" value="......" onfocus="this.value = '';" />

      </td>

      <td width="70" align="right" valign="middle">

        <input name="submit" type="submit" class="button" value="Отправить" /><input name="login" type="hidden" id="login" value="submit" />

      </td>

    </tr>

  </table>

</form>

HTML;

}

?>