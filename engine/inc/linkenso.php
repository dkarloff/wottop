<?php

/*
=============================================================================
 Файл: linkenso.php (backend) версия 2.0
-----------------------------------------------------------------------------
 Автор: Фомин Александр Алексеевич, mail@mithrandir.ru
-----------------------------------------------------------------------------
 Назначение: генератор кода для вставки модуля в шаблон fullstory.tpl
=============================================================================
*/

    // Антихакер
    if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
            die( "Hacking attempt!" );
    }

    echoheader('linkenso', 'Генератор кода для вставки модуля в шаблон');
?>
            <div style="padding-top: 5px; padding-bottom: 2px;">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td width="4"><img height="4" width="4" border="0" src="engine/skins/images/tl_lo.gif"></td>
                            <td background="engine/skins/images/tl_oo.gif"><img height="4" width="1" border="0" src="engine/skins/images/tl_oo.gif"></td>
                            <td width="6"><img height="4" width="6" border="0" src="engine/skins/images/tl_ro.gif"></td>
                        </tr>
                        <tr>
                            <td background="engine/skins/images/tl_lb.gif"><img height="1" width="4" border="0" src="engine/skins/images/tl_lb.gif"></td>
                            <td bgcolor="#ffffff" style="padding: 5px;">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td height="29" bgcolor="#efefef" style="padding-left: 10px;"><div class="navigation">Генератор кода для вставки модуля</div></td>
                                            <td height="29" bgcolor="#efefef" align="right" style="padding: 5px;"><a title="Проверить наличие обновлений?" href="http://alaev.info/blog/post/3982?from=LinkEnsoPro">LinkEnso v.2.0</a> © 2012 Блог АлаичЪ'а - разработка и поддержка модуля</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="unterline"></div>
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="200" style="padding: 2px;">Количество ссылок:</td>
                                            <td style="padding: 2px;">
                                                <input class="edit bk" type="text" name="linkenso_links" id="linkenso_links" value="3" />
                                                <a onmouseover="showhint('Общее количество ссылок, выводимых модулем.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">Какие новости показывать:</td>
                                            <td style="padding: 2px;">
                                                <select class="edit bk" name="linkenso_date" id="linkenso_date">
                                                    <option value="old">предыдущие новости</option>
                                                    <option value="new">свежие новости</option>
                                                </select>
                                                <a onmouseover="showhint('Опция для отображения свежих или предыдущих постов.<br /><strong>предыдущие новости</strong> - В ссылках будут выведены предыдущие новости.<br /><strong>свежие новости</strong> - В ссылках будут выведены свежие новости.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">Закольцевать ссылки:</td>
                                            <td style="padding: 2px;">
                                                <select class="edit bk" name="linkenso_ring" id="linkenso_ring">
                                                    <option value="yes">да</option>
                                                    <option value="no">нет</option>
                                                </select>
                                                <a onmouseover="showhint('Закольцевать ли ссылки.<br /><strong>да</strong> - Ссылки будут закольцованы, т.к. в блоке свежих статей  в последних новостях будут отображены самые первые новости.<br /><strong>нет</strong> - Ссылки не будут закольцованы, если не будет найдено свежих или предыдущих ссылок, модуль ничего не выведет.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">Сканировать категории:</td>
                                            <td style="padding: 2px;">
                                                <select class="edit bk" name="linkenso_scan" id="linkenso_scan">
                                                    <option value="all_cat">все категории</option>
                                                    <option value="same_cat">текущая категория</option>
                                                    <option value="global_cat">глобальная категория</option>
                                                </select>
                                                <a onmouseover="showhint('Глубина сканирования категорий для вывода ссылок.<br /><strong>все категории</strong> - В модуле будут выводиться ссылки на новости из всех категорий.<br /><strong>текущая категория</strong> - В модуле будут выводиться ссылки на новости из той же категории, что и текущая.<br /><strong>глобальная категория</strong> - В модуле будут выводиться ссылки на новости из самой корневой категории для текущей новости.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>

                                        <tr><td colspan="2"><div class="hr_line"></div></td></tr>
                                        
                                        <tr>
                                            <td style="padding: 2px;">Анкор:</td>
                                            <td style="padding: 2px;">
                                                <select class="edit bk" name="linkenso_anchor" id="linkenso_anchor">
                                                    <option value="name">название новости</option>
                                                    <option value="title">title новости</option>
                                                </select>
                                                <a onmouseover="showhint('Принцип вывода анкора ссылки.<br /><strong>название новости</strong> - В ссылках будут выведены заголовки новостей.<br /><strong>title новости</strong> - В ссылках будут выведены title новостей.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">Атрибут title ссылок:</td>
                                            <td style="padding: 2px;">
                                                <select class="edit bk" name="linkenso_title" id="linkenso_title">
                                                    <option value="title">title новости</option>
                                                    <option value="name">название новости</option>
                                                    <option value="empty">оставить пустым</option>
                                                </select>
                                                <a onmouseover="showhint('Принцип вывода атрибута title ссылки.<br /><strong>title новости</strong> - В title будут выведены title новостей.<br /><strong>название новости</strong> - В title будут выведены заголовки новостей.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        
                                        <tr><td colspan="2"><div class="hr_line"></div></td></tr>
                                        
                                        <tr>
                                            <td style="padding: 2px;">Изображение:</td>
                                            <td style="padding: 2px;">
                                                <select class="edit bk" name="linkenso_image_select" id="linkenso_image_select">
                                                    <option value="full_story">1-е изображение полной новости</option>
                                                    <option value="short_story">1-е изображение краткой новости</option>
                                                    <option value="xfield">значение дополнительного поля</option>
                                                </select>
                                                <input class="edit bk" type="hidden" name="linkenso_image" id="linkenso_image" value="" />
                                                <a onmouseover="showhint('Принцип вывода изображения из новости. При выборе изображения из дополнительного поля, необходимо ввести латинскими буквами название нужного дополнительного поля.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="padding: 2px;">Обрезка текста:</td>
                                            <td style="padding: 2px;">
                                                <input class="edit bk" name="linkenso_limit" id="linkenso_limit" />
                                                <a onmouseover="showhint('Количество символов, выводимых из полной или краткой новости.', this, event, '250px')" class="hintanchor" href="#">[?]</a>
                                            </td>
                                        </tr>
                                        
                                        <tr><td colspan="2"><div class="hr_line"></div></td></tr>
                                        
                                        <tr>
                                            <td style="padding: 2px;">Код для вставки в <strong>fullstory.tpl</strong></td>
                                            <td style="padding: 2px;">
                                                <input style="border: 1px solid #ccc; color: #666; width: 100%; font-size: 16px; line-height: 17px;" name="linkenso_code" id="linkenso_code" value="{include file='engine/modules/linkenso.php?post_id={news-id}'}" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                
                                
                                <script type="text/javascript">
                                    var linkenso_options = [
                                         "links",                                     
                                         "date",                                     
                                         "ring",                                     
                                         "scan",                                     
                                         "anchor",                                     
                                         "title",                                     
                                         "image",
                                         "limit"
                                    ];
                                    
                                    document.getElementById("linkenso_image_select").onchange = function(){
                                        switch(document.getElementById("linkenso_image_select").value)
                                        {
                                            case 'short_story':
                                                document.getElementById("linkenso_image").type = 'hidden';
                                                document.getElementById("linkenso_image").value = 'short_story';
                                                break;
                                                
                                            case 'full_story':
                                                document.getElementById("linkenso_image").type = 'hidden';
                                                document.getElementById("linkenso_image").value = 'full_story';
                                                break;
                                                
                                            case 'xfield':
                                                document.getElementById("linkenso_image").value = '';
                                                document.getElementById("linkenso_image").type = 'text';
                                                break;
                                                
                                            default:
                                                break;
                                        }
                                        
                                        recalculate_code();
                                    };

                                    for(i = 0; i < linkenso_options.length; i = i+1)
                                    {
                                        document.getElementById("linkenso_" + linkenso_options[i]).onchange = function(){
                                            recalculate_code();
                                        };
                                    }
                                        
                                    function recalculate_code()
                                    {

                                        document.getElementById("linkenso_code").value = "{include file='engine/modules/linkenso.php?post_id={news-id}";
                                        
                                        for(var i = 0; i < linkenso_options.length; i = i+1)
                                        {                                            
                                            if(document.getElementById("linkenso_" + linkenso_options[i]).value)
                                            {
                                                document.getElementById("linkenso_code").value = document.getElementById("linkenso_code").value + "&" + linkenso_options[i] + "=" + document.getElementById("linkenso_" + linkenso_options[i]).value;
                                            }
                                        }

                                        document.getElementById("linkenso_code").value = document.getElementById("linkenso_code").value + "'}";
                                    }
                                </script>
                            </td>
                            <td background="engine/skins/images/tl_rb.gif"><img height="1" width="6" border="0" src="engine/skins/images/tl_rb.gif"></td>
                        </tr>
                        <tr>
                            <td><img height="6" width="4" border="0" src="engine/skins/images/tl_lu.gif"></td>
                            <td background="engine/skins/images/tl_ub.gif"><img height="6" width="1" border="0" src="engine/skins/images/tl_ub.gif"></td>
                            <td><img height="6" width="6" border="0" src="engine/skins/images/tl_ru.gif"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

<?php

        // Отображение подвала админского интерфейса
        echofooter();
  
?>