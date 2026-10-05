"""Collect the latest 200 public news articles and licensed cover images.
Raw source material stays in the ignored backups directory, never in published HTML.
"""
import concurrent.futures, hashlib, json, re, time
from pathlib import Path
from urllib.parse import urljoin, urlparse
import requests
from bs4 import BeautifulSoup
ROOT=Path(__file__).resolve().parents[1]
CACHE=ROOT/'backups/news-import'; CACHE.mkdir(parents=True,exist_ok=True)
BASE='https://wotexpress.info'
def fetch(url):
    if urlparse(url).hostname!='wotexpress.info': raise ValueError('Unexpected source host')
    for attempt in range(3):
        try:
            with requests.Session() as s:
                s.trust_env=False
                r=s.get(url,timeout=35); r.raise_for_status(); return r
        except requests.RequestException:
            if attempt==2: raise
            time.sleep(1+attempt)
def listing(page):
    url=BASE+'/mir-tankov/news/'+(f'page{page}/' if page>1 else '')
    soup=BeautifulSoup(fetch(url).content,'html.parser')
    return [urljoin(BASE,a['href']) for a in soup.select('article.news-rows_item > a[href]')]
def article(item):
    rank,url=item
    soup=BeautifulSoup(fetch(url).content,'html.parser')
    data=None
    for script in soup.select('script[type="application/ld+json"]'):
        try:
            candidate=json.loads(script.string or script.get_text())
            if candidate.get('@type')=='NewsArticle': data=candidate; break
        except (ValueError,AttributeError): pass
    if not data: raise ValueError('Missing metadata: '+url)
    body=soup.select_one('.newsid_text')
    if body is None: raise ValueError('Missing article: '+url)
    for el in body.select('script,style,iframe'): el.decompose()
    image=data.get('image',{}); image=image.get('url') if isinstance(image,dict) else image
    if not image: raise ValueError('Missing cover: '+url)
    image=urljoin(BASE,image)
    img=fetch(image)
    if not img.headers.get('Content-Type','').startswith('image/'): raise ValueError('Not an image')
    suffix=Path(urlparse(image).path).suffix.lower()
    if suffix not in ('.jpg','.jpeg','.png','.webp','.gif'): raise ValueError('Unexpected format')
    key=hashlib.sha256(url.encode()).hexdigest()[:16]
    image_path='/uploads/news/'+key+suffix
    (ROOT/image_path.lstrip('/')).write_bytes(img.content)
    return dict(rank=rank,url=url,title=data['headline'],date=data['datePublished'],description=data.get('description',''),text=body.get_text('\n',strip=True),image=image_path,original_image=image)
def main():
    urls=[]
    with concurrent.futures.ThreadPoolExecutor(max_workers=4) as pool:
        for group in pool.map(listing,range(1,19)):
            for url in group:
                if url not in urls: urls.append(url)
    if len(urls)<200: raise RuntimeError(f'Only {len(urls)} articles')
    result=[]
    with concurrent.futures.ThreadPoolExecutor(max_workers=4) as pool:
        for item in pool.map(article,enumerate(urls[:200],1)):
            result.append(item)
            if len(result)%25==0: print('Collected',len(result),flush=True)
    (CACHE/'sources.json').write_text(json.dumps(result,ensure_ascii=False,indent=2),encoding='utf-8')
    print('Saved',len(result),'sources; newest:',result[0]['date'],'oldest:',result[-1]['date'],flush=True)
if __name__=='__main__': main()
