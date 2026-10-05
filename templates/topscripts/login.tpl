[not-group=5]
<details class="account-dropdown"><summary>Мой профиль</summary><div class="account-panel"><a href="{profile-link}">Профиль</a><a href="{pm-link}">Сообщения ({new-pm})</a><a href="{addnews-link}">Добавить материал</a>[admin-link]<a href="{admin-link}">Админпанель</a>[/admin-link]<a href="{logout-link}">Выйти</a></div></details>
[/not-group]
[group=5]
<button class="login-trigger" type="button" data-login-open>Войти <span>+</span></button>
<dialog id="login-dialog"><button class="dialog-close" type="button" data-login-close aria-label="Закрыть">×</button><p class="eyebrow">WOTTOP / ЛИЧНЫЙ КАБИНЕТ</p><h2>С возвращением</h2><p>Войди, чтобы участвовать в обсуждениях.</p><form method="post" action="/"><label>Логин<input name="login_name" autocomplete="username" required></label><label>Пароль<input type="password" name="login_password" autocomplete="current-password" required></label><input type="hidden" name="login" value="submit"><button class="button primary" type="submit">Войти в аккаунт →</button></form><div class="login-links"><a href="{registration-link}">Регистрация</a><a href="{lostpassword-link}">Забыли пароль?</a></div></dialog>
[/group]
