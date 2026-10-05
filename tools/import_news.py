"""Publish reviewed factual briefs; never publish raw source articles.
Usage: python tools/import_news.py --publish
"""
import argparse,datetime,hashlib,html,json,re
from pathlib import Path
import pymysql
ROOT=Path(__file__).resolve().parents[1]; CACHE=ROOT/'backups/news-import'
def main():
    ap=argparse.ArgumentParser();ap.add_argument('--publish',action='store_true');args=ap.parse_args()
    sources=json.loads((CACHE/'sources.json').read_text(encoding='utf-8'))
    briefs=[]
    for line in (CACHE/'briefs.tsv').read_text(encoding='utf-8-sig').splitlines():
        if line.strip():
            rank,title,summary=line.split('\t',2);briefs.append({'rank':int(rank),'title':title,'summary':summary})
    indexed={b['rank']:b for b in briefs}
    assert len(sources)==len(briefs)==len(indexed)==200,'Expected exactly 200 unique briefs'
    assert set(indexed)==set(range(1,201)),'Missing ranks'
    for s in sources:
        b=indexed[s['rank']]
        assert 8<len(b['title'])<=200 and len(b['summary'])>=120
        assert len(b['summary'].split())<150,'Brief exceeds editorial limit'
        assert (ROOT/s['image'].lstrip('/')).is_file()
    if not args.publish:
        print('Validated 200 briefs and cover images');return
    connection=pymysql.connect(host='MySQL-8.4',user='root',password='',database='wottop',charset='utf8mb4',autocommit=True)
    manifest=[]
    with connection.cursor() as cur:
        cur.execute("SELECT id FROM dle_category WHERE alt_name='news'");category=cur.fetchone()[0]
        for s in sources:
            b=indexed[s['rank']];key=hashlib.sha256(s['url'].encode()).hexdigest()[:16];slug='news-'+key
            cur.execute('SELECT id FROM dle_post WHERE alt_name=%s',(slug,));existing=cur.fetchone()
            if existing: post_id=existing[0]
            else:
                date=datetime.datetime.fromisoformat(s['date']).astimezone(datetime.timezone(datetime.timedelta(hours=3))).replace(tzinfo=None)
                summary=html.escape(b['summary']);title=b['title']
                short=f'<p><img src="{s["image"]}" alt="{html.escape(title,quote=True)}" loading="lazy"></p><p>{summary}</p>'
                full=''
                cur.execute('INSERT INTO dle_post (autor,date,short_story,full_story,xfields,title,descr,keywords,category,alt_name,approve,allow_main,allow_br,metatitle) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,1,0,0,%s)',('Редакция WOTTOP',date,short,full,'',title,b['summary'][:297],'',str(category),slug,title))
                post_id=cur.lastrowid
            cur.execute('SELECT eid FROM dle_post_extras WHERE news_id=%s',(post_id,))
            if not cur.fetchone():cur.execute('INSERT INTO dle_post_extras (news_id) VALUES (%s)',(post_id,))
            cur.execute('SELECT id FROM dle_post_extras_cats WHERE news_id=%s AND cat_id=%s',(post_id,category))
            if not cur.fetchone():cur.execute('INSERT INTO dle_post_extras_cats (news_id,cat_id) VALUES (%s,%s)',(post_id,category))
            manifest.append({'id':post_id,'source':s['url'],'path':f'/news/{post_id}-{slug}.html','rank':s['rank']})
    connection.close()
    (CACHE/'manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2),encoding='utf-8')
    print('Published or verified',len(manifest),'news articles')
if __name__=='__main__':main()
