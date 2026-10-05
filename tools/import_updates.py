"""Add reviewed existing news to Updates without duplicate articles or source links."""
import datetime, hashlib, json
from pathlib import Path
import pymysql

ROOT = Path(__file__).resolve().parents[1]
CACHE = ROOT / 'backups/news-import'
sources = json.loads((CACHE / 'updates-sources.json').read_text(encoding='utf-8'))
connection = pymysql.connect(host='MySQL-8.4', user='root', database='wottop', charset='utf8mb4')
try:
    with connection.cursor() as cursor:
        posts = []
        for source in sources:
            slug = 'news-' + hashlib.sha256(source['url'].encode()).hexdigest()[:16]
            cursor.execute('SELECT id,category,short_story,full_story FROM dle_post WHERE alt_name=%s', (slug,))
            post = cursor.fetchone()
            if not post:
                raise ValueError('Review and import missing article before categorizing: ' + slug)
            assert (ROOT / source['image'].lstrip('/')).is_file()
            posts.append(post)
        backup = CACHE / ('before-updates-' + datetime.datetime.now().strftime('%Y%m%d-%H%M%S') + '.json')
        backup.write_text(json.dumps(posts, ensure_ascii=False), encoding='utf-8')
        cursor.execute("SELECT id FROM dle_category WHERE alt_name='obnovleniya'")
        existing = cursor.fetchone()
        if existing:
            category = existing[0]
        else:
            cursor.execute("INSERT INTO dle_category(name,alt_name,descr,keywords,fulldescr,metatitle,news_sort,news_msort,news_number,short_tpl,full_tpl) VALUES(%s,'obnovleniya',%s,'','',%s,'date','DESC',12,'','news-full')", ('Обновления', 'Патчи Мира танков: общие тесты, баланс техники и изменения игры.', 'Обновления Мира танков — патчи и общие тесты | WOTTOP'))
            category = cursor.lastrowid
        for ident, categories, short, full in posts:
            assigned = categories.split(',')
            if str(category) not in assigned:
                assigned.append(str(category))
                cursor.execute('UPDATE dle_post SET category=%s WHERE id=%s', (','.join(assigned), ident))
            cursor.execute('SELECT id FROM dle_post_extras_cats WHERE news_id=%s AND cat_id=%s', (ident, category))
            if not cursor.fetchone():
                cursor.execute('INSERT INTO dle_post_extras_cats(news_id,cat_id) VALUES(%s,%s)', (ident, category))
        connection.commit()
        print('Updates category:', category, 'Articles:', len(posts))
except Exception:
    connection.rollback()
    raise
finally:
    connection.close()
