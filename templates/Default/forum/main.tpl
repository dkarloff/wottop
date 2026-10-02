<script type='text/javascript'>
function forum_profile()
{
    var menu = new Array();

	menu[0] = '[subscription-link]Мои Подписки[/subscription-link]';
    menu[1] = '[topics-link]Мои Темы[/topics-link]';
    menu[2] = '[posts-link]Мои Сообщения[/posts-link]';
    
    return menu;
};
</script>

<style type="text/css" media="all">@import url({THEME}/forum/dle-forum.css);</style>

<div align="left" class="dle_forum">

    <div style="font-weight:bold; font-size:12px;"><img src="{THEME}/forum/images/nav.gif" border="0" width="10px" height="10px" /> {BOARD HEADER}</div>

  <br />

  <div style="font-size:10px; background-color:rgb(240,245,250); padding:7px; border-width:1px; border-color:rgb(194,207,223); border-style:solid;"><div style="float:left;">{last_visit}</div><div align="right">[search-link]Поиск[/search-link] | [getnew-link]Новые сообщения[/getnew-link] | [profile]<a href="#" onclick="return dropdownmenu(this, event, forum_profile(), '170px');" onmouseout="delayhidemenu();">Профиль</a> |[/profile] [rss]RSS[/rss]</div></div>

  <br />

  {BOARD}

  {STATS}
  
  <br />

  <table width="100%" cellspacing="0" cellpadding="0" style="font-size:10px; background-color:rgb(240,245,250); padding:7px; border-width:1px; border-color:rgb(194,207,223); border-style:solid;">
    <tr>
        <td style="padding:7px;" width="45%" align="left" nowrap="nowrap">&nbsp;</td>
        <td style="padding:7px;" width="10%" align="center" nowrap="nowrap"><strong> <div class="copyright1">[textversion]Текстовая версия[/textversion]</div></strong></td>
        <td style="padding:7px;" width="45%" align="right" nowrap="nowrap">Сейчас: {now_time}</td>
    </tr>
</table>
<br />
</div><br />