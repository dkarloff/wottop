[not-group=5]

	[admin-link]<li class="item-506"> <a href="{admin-link}" ><b>Админпанель</b></a></li> 	[/admin-link]
	<li class="item-506"> <a href="{profile-link}">Мой профиль</a> </li> 
    <li class="item-506"> <a href="{pm-link}">Сообщения: ({new-pm}|{all-pm})</a> </li>	
	<li class="item-506"> <a href="{addnews-link}">Опубликовать</a> </li> 
    <li class="item-506"> <a href="{logout-link}">Выход</a> </li> 
[/not-group]
[group=5]
<li class="item-506"> <a href="#popup-vhod" name="modal" >Вход</a> </li> 
             <li class="item-506"> <a href="/index.php?do=register" >Создать аккаунт</a> </li> 

<script type="text/javascript"> 
$(document).ready(function() { 
//select all the a tag with name equal to modal 
$('a[name=modal]').click(function(e) { 
//Cancel the link behavior 
e.preventDefault(); 
//Get the A tag 
var id = $(this).attr('href'); 
//Get the screen height and width 
var maskHeight = $(document).height(); 
var maskWidth = $(window).width(); 
//Set heigth and width to mask to fill up the whole screen 
$('#mask').css({'width':maskWidth,'height':maskHeight}); 
//transition effect 
$('#mask').fadeIn(1000); 
$('#mask').fadeTo("slow",0.3); 
//Get the window height and width 
var winH = $(window).height(); 
var winW = $(window).width(); 
//Set the popup window to center 
$(id).css('top', winH/2-$(id).height()/2); 
$(id).css('left', winW/2-$(id).width()/2); 
//transition effect 
$(id).fadeIn(1000); 
}); 
//if close button is clicked 
$('.window .close').click(function (e) { 
//Cancel the link behavior 
e.preventDefault(); 
$('#mask, .window').hide(); 
}); 
//if mask is clicked 
$('#mask').click(function () { 
$(this).hide(); 
$('.window').hide(); 
}); 
}); 
</script>



<!--popup-->
<div id="boxes"> 
<div id="popup-vhod" class="window"> 

<!-- block --> 
<div class="login-box">
<div class="log-box-l">
<div class="log-box-r">
<div class="close-div"><a href="#" class="close"></a></div>
<div class="text270deg">Авторизация</a></div>
<div class="popup-body">
<!-- ФОРМА ВХОДА --> 
<div class="log-vhod">

<form class="flogin" method="post" action=""> 
<div class="lfield" >
<p>Логин (<a href="{registration-link}">Регистрация</a>):</p>
<input class="loginField" type="text" name="login_name" id="login_name"  onblur="if (value == '') {value = 'Your username'}" onfocus="if (value == 'Your username') {value =''}" value="Your username"/>
<p>Пароль (<a href="{lostpassword-link}">Забыли?</a>):</p>
<input class="loginField" type="text" name="login_password" id="login_password" onblur="if (value == '') {value = 'Your Password'}" onfocus="if (value == 'Your Password') {value =''}" value="Your Password"/>
</div>

<div valign="top">
<input class="but-log" onclick="submit();" name="image" alt="Войти" type="button" value=""/>
</div>

<input name="login" type="hidden" id="login" value="submit" />
</form>

</div>
<!-- END ФОРМА ВХОДА -->  
<div class="clr"></div>
</div>

</div></div></div> 
</div>
<!-- /block --> 

</div> 
<div id="mask"></div> 
<!--/popup-->
[/group]