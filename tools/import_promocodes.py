"""Import reviewed bonus-code facts from the first page of the supplied source."""
import datetime,html,json
from pathlib import Path
import pymysql
ROOT=Path(__file__).resolve().parents[1]; CACHE=ROOT/'backups/news-import'
sources=json.loads((CACHE/'promocodes-sources.json').read_text(encoding='utf-8'))
# source index, code, factual reward description, condition, known expiry
records=[
(0,'MT2026TDAY','Трое суток премиум аккаунта, стиль «Классика жанра» и два часовых резерва, удваивающих боевой опыт.','Подарок ко Дню танкиста 2026.','2026-10-16T23:59:00+03:00'),
(1,'BDAYTANKISU','Пять праздничных посылок, трое суток премиум аккаунта и дополнительное исключение одной карты на 16 дней.','Отметка ×16 у слота означает срок в днях, а не шестнадцать слотов.',None),
(2,'MTWORLDCUP26','Двое суток премиума и стиль «Финт». По одному часовому резерву: +50% к серебру, +50% к боевому опыту и +200% к свободному опыту и опыту экипажа.','Одноразовая активация при соблюдении условий акции.',None),
(3,'MED26MT','Сутки премиума и «Цветущий сад». По два часовых резерва: +200% к свободному опыту и опыту экипажа, +50% к боевому опыту.','Праздничное предложение из июльской публикации.',None),
(4,'0307BELARUSDAY','Оформление «Пионовый сад», сутки премиума и два часовых резерва на дополнительное серебро (+50%).','Код приурочен ко Дню независимости Беларуси.',None),
(5,'SUMMER26MT','150 крышечек Летней ярмарки, сутки премиума и оформление «Цветущий сад».','Летняя ярмарка 2026 завершилась 8 июля. Возможность выдачи наград сейчас не подтверждена.',None),
(6,'1206RUSSIADAY','Оформление «Небесный цветник», сутки премиума и два часовых резерва на +50% к серебру.','Подарок из июньской акции ко Дню России.',None),
(7,'PAY4YCVMT','В июньской подборке конкретный набор наград для этого кода не указан.','Проверьте доступность и состав подарка на официальной странице активации.',None),
(7,'25O8UFZMT','Источник перечисляет код без отдельного описания выдаваемого имущества.','Код опубликован в подборке за июнь 2026; действительность сейчас не подтверждена.',None),
(7,'BP43P1HMT','Состав подарка в исходной подборке не раскрыт.','Код из июньского списка. Возможны ограничения по аккаунту и числу активаций.',None),
(7,'6EW2EJDMT','Для этого кода источник не уточняет вид и количество наград.','Публикация датирована июнем 2026. Результат определяется на странице активации.',None),
(7,'OCVPGTGMT','В подборке приведён код, но не перечислены награды.','Срок и текущая доступность не подтверждены; учитывайте ограничения акции.',None),
(7,'5BACKMIRTANKOV','Специальное предложение для вернувшихся игроков; точный состав награды источник не называет.','Предназначен для аккаунтов, которые не заходили в игру более 30 дней.',None),
(7,'MTSTART','Стартовые бонусы для нового аккаунта. Точный состав набора в источнике не указан.','Это инвайт-код: вводится при регистрации, а не как обычный код существующего аккаунта.',None),
(8,'MAXMT','Трое суток премиум аккаунта.','Код выпущен в связи с появлением официального канала игры в MAX.',None),
(9,'VDAY2026MT','Сутки премиума, «Знамя Победы», по три декали «Вечный огонь» и надписи «Помним!».','Праздничный набор ко Дню Победы 2026.',None),
(10,'COSMOSDAY26MT','Сутки премиума и «Созвездие СССР». По два часовых резерва: +200% к комбинированному опыту и +50% к боевому опыту.','Предложение из апрельской акции ко Дню космонавтики.',None),
(11,'ATACIIFREE','Открывает задачу на 2500 чистого опыта. По публикации: ATAC II с вероятностью 0,1%, иначе 100 000 серебра (99,9%).','Танк не гарантирован. Описание относится к акции из мартовской публикации.',None),
]
conn=pymysql.connect(host='MySQL-8.4',user='root',database='wottop',charset='utf8mb4',autocommit=True)
manifest=[]
with conn.cursor() as c:
 c.execute("INSERT INTO dle_category(name,alt_name,descr,keywords,fulldescr,metatitle,news_sort,news_msort,news_number,short_tpl,full_tpl) SELECT %s,'promokody-wot',%s,'','',%s,'date','DESC',24,'promo-card','promo-full' WHERE NOT EXISTS(SELECT 1 FROM dle_category WHERE alt_name='promokody-wot')",('Промокоды WOT','Коды для Мира танков: награды и условия','Промокоды WOT — Мир танков | WOTTOP'))
 c.execute("SELECT id FROM dle_category WHERE alt_name='promokody-wot'");category=c.fetchone()[0]
 for idx,code,reward,condition,expiry in records:
  source=sources[idx];slug='code-'+code.lower();date=datetime.datetime.fromisoformat(source['date']).replace(tzinfo=None)
  status=(f'<span class="promo-status" data-expires="{expiry}">По анонсу — до 16.10.2026</span>' if expiry else '<span class="promo-status unverified">Срок не указан</span>')
  short=f'{status}<p class="promo-reward">{html.escape(reward)}</p><p class="promo-condition">{html.escape(condition)}</p><div class="promo-actions"><button type="button" data-copy-code="{code}" aria-label="Скопировать код {code}">Копировать код</button></div><span class="copy-status" role="status" aria-live="polite"></span>'
  full='<h2>Как использовать</h2><p>Скопируйте код и перейдите на официальный сайт игры. Для бонус-кода войдите в свой аккаунт и воспользуйтесь разделом активации. Инвайт-код предназначен для ввода при регистрации нового аккаунта.</p><p>Код может быть недоступен из-за завершения акции, лимита активаций или условий для аккаунта. Публикация в каталоге не гарантирует успешную активацию.</p>'
  if expiry:
   full+='<p>Срок действия: до 16 октября 2026 года, 23:59 МСК.</p>'
  c.execute('SELECT id FROM dle_post WHERE alt_name=%s',(slug,));old=c.fetchone()
  if old: post_id=old[0]
  else:
   c.execute('INSERT INTO dle_post(autor,date,short_story,full_story,xfields,title,descr,keywords,category,alt_name,approve,allow_main,allow_br) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,1,0,0)',('Редакция WOTTOP',date,short,full,'',code,reward[:297],'',str(category),slug));post_id=c.lastrowid
  c.execute('SELECT eid FROM dle_post_extras WHERE news_id=%s',(post_id,))
  if not c.fetchone(): c.execute('INSERT INTO dle_post_extras(news_id) VALUES(%s)',(post_id,))
  c.execute('SELECT id FROM dle_post_extras_cats WHERE news_id=%s AND cat_id=%s',(post_id,category))
  if not c.fetchone():c.execute('INSERT INTO dle_post_extras_cats(news_id,cat_id) VALUES(%s,%s)',(post_id,category))
  manifest.append({'id':post_id,'code':code,'path':f'/promokody-wot/{post_id}-{slug}.html'})
conn.close();(CACHE/'promocodes-manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2),encoding='utf-8');print('Imported',len(manifest),'codes; category',category)
