<!DOCTYPE html>
<html lang="ru">
<head>
{headers}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#111314">
<link rel="stylesheet" href="{THEME}/style/engine.css">
<link rel="stylesheet" href="{THEME}/style/modern.css?v=7">
<script defer src="{THEME}/modern.js?v=7"></script>
</head>
<body>
{AJAX}
<a class="skip-link" href="#materials">Перейти к материалам</a>
<header class="site-header">
<a class="brand" href="/" aria-label="WOTTOP — главная"><span class="brand-mark">W</span><span>WOT<span class="accent">TOP</span><small>WORLD OF TANKS COMMUNITY</small></span></a>
<nav class="header-nav" aria-label="Основная навигация"><a href="/" data-nav>Главная</a><a href="/news/" data-nav>Новости</a><a href="/obnovleniya/" data-nav>Обновления</a><a href="/promokody-wot/" data-nav>Промокоды</a><a href="/modi-wot/" data-nav>Моды</a><a href="/guide_wot/" data-nav>Гайды</a><a href="/video-wot/" data-nav>Видео</a></nav>
<form class="search-form" action="/index.php" method="get" role="search"><input type="hidden" name="do" value="search"><input type="hidden" name="subaction" value="search"><input name="story" aria-label="Поиск по сайту" placeholder="Найти мод, танк или гайд…" required><button aria-label="Искать" type="submit">⌕</button></form>
<div class="account">{login}</div>
<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="catalog-menu" aria-label="Открыть каталог">☰ <span>Каталог</span></button>
</header>
<div class="site-layout">
<aside class="sidebar" id="catalog-menu">
<p class="nav-label">ТВОЙ АРСЕНАЛ</p>
<nav class="catalog-nav" aria-label="Категории материалов">
<a href="/" data-nav><span>▦</span>Все материалы</a>
<a href="/news/" data-nav><span>▣</span>Новости</a>
<a href="/obnovleniya/" data-nav><span>⟳</span>Обновления</a>
<a href="/promokody-wot/" data-nav><span>◇</span>Промокоды WOT</a>
<a href="/modi-wot/" data-nav><span>⬡</span>Моды для WoT</a>
<a href="/priceli-wot/" data-nav><span>⊕</span>Прицелы</a>
<a href="/shkurki-wot/" data-nav><span>◈</span>Шкурки</a>
<a href="/zony-probutiya-tankov/" data-nav><span>◎</span>Зоны пробития</a>
<a href="/angari-wot/" data-nav><span>⌂</span>Ангары</a>
</nav>
<p class="nav-label second-label">БАЗА ЗНАНИЙ</p>
<nav class="catalog-nav" aria-label="База знаний">
<a href="/guide_wot/" data-nav><span>▤</span>Гайды по танкам</a>
<a href="/statii-wot/" data-nav><span>≡</span>Статьи и советы</a>
<a href="/video-wot/" data-nav><span>▷</span>Видео</a>
<a href="/other-wot/" data-nav><span>⋯</span>Разное</a>
</nav>
<div class="sidebar-note"><span class="note-icon">i</span><strong>Перед выходом в бой</strong><p>Проверь версию игры в описании мода. В каталоге есть архивные материалы.</p><a href="/statii-wot/">Полезные советы <span>+</span></a></div>
{include file="engine/modules/sape-sidebar.php"}
<a class="feedback-link" href="/index.php?do=feedback">Обратная связь +</a>
</aside>
<main class="main-content">
[available=main]
<section class="hero">
<div class="hero-content"><p class="eyebrow"><span></span> СООБЩЕСТВО WORLD OF TANKS</p><h1>ТВОЙ ТАНК.<br>ТВОИ ПРАВИЛА<span class="accent">.</span></h1><p class="hero-description">Собери свой арсенал. Моды, прицелы и гайды,<br class="desktop-break"> которые помогут разобраться в игре.</p><div class="hero-actions"><a class="button primary" href="/modi-wot/">Выбрать моды <span>+</span></a><a class="button secondary" href="/guide_wot/">Изучить гайды <span>→</span></a></div></div>
<div class="hero-caption"><span>В ФОКУСЕ</span><strong>Т-62А</strong><small>Советский средний танк</small></div>
<div class="hero-bottom"><span>Техника. Тактика. Твой стиль.</span><span>WOTTOP / COMMUNITY PORTAL</span></div>
</section>
<div class="category-tiles"><a href="/modi-wot/"><span class="tile-icon">⬡</span><span><strong>Настрой под себя</strong><small>Моды и интерфейс</small></span><b>+</b></a><a href="/priceli-wot/"><span class="tile-icon">⊕</span><span><strong>Держи цель</strong><small>Прицелы для WoT</small></span><b>+</b></a><a href="/guide_wot/"><span class="tile-icon">▤</span><span><strong>Знай свою технику</strong><small>Гайды и тактика</small></span><b>+</b></a></div>
{include file="home-news.tpl"}
{include file="road-banner.tpl"}
[/available]
<section id="materials" class="materials">
[available=main]<div class="section-heading"><div><p class="eyebrow">ИЗ АРХИВА СООБЩЕСТВА</p><h2>Всё для твоего следующего боя</h2></div><span class="section-meta">МАТЕРИАЛЫ WOTTOP</span></div><nav class="filter-tabs" aria-label="Выбор материалов"><a href="/" class="active">Все материалы</a><a href="/modi-wot/">Моды</a><a href="/priceli-wot/">Прицелы</a><a href="/guide_wot/">Гайды</a><a href="/statii-wot/">Статьи</a><a href="/video-wot/">Видео</a></nav>[/available]
[not-available=main]<div class="breadcrumbs">{speedbar}</div>[/not-available]
[available=cat]<div class="section-heading catalog-heading"><div><p class="eyebrow">КАТАЛОГ WOTTOP</p><h1>{category-title}</h1></div></div>[/available]
[available=cat][category=12]{include file="updates-intro.tpl"}[/category][/available]
[available=cat][category=11]{include file="promo-intro.tpl"}[/category][/available]
{info}
<div class="content-body">{content}</div>
</section>
[available=main]{include file="about-portal.tpl"}[/available]
<footer class="site-footer"><a class="footer-brand" href="/">WOT<span class="accent">TOP</span></a><p>Независимое сообщество игроков World of Tanks.<br>Игровые названия и изображения принадлежат их правообладателям.</p><a href="/index.php?do=feedback">Связаться с нами +</a><a href="#" class="back-top">↑ Наверх</a></footer>
</main>
</div>
</body>
</html>
