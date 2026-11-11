(() => {
  'use strict';
  const section = document.querySelector('[data-sks-partners]');
  const links = section?.querySelector('#exchange-links');
  if (!links || section.dataset.sksReady) return;
  const excluded = /admin|search|login|logout|privacy|policy|terms|legal|agreement|soglas|oferta|konfid|politika|register|registration|auth|cart|basket|checkout|404/i.test(location.pathname) || location.search || /404|not found|не найден[ао]?/i.test(document.title);
  if (excluded) { section.remove(); return; }
  const isHome = location.pathname === '/';
  let placementAnchor = isHome ? document.querySelector("#materials #dle-content > article:nth-of-type(4)") : null;
  let position = "afterend";
  if (!isHome) {
    const main = document.querySelector('main, #content, #dle-content, .td-main-content-wrap, .site-main, [role=main]');
    if (!main) { section.remove(); return; }
    const content = main.querySelector('.story-body, .article-body, .article-content, .content-article, .entry-content, .main-column, .content-column, .td-ss-main-content') || main;
    let container = content;
    const eligible = el => el !== section && !el.contains(section) && el.matches('section, article, div, p, .td-block-row') && !el.matches('.breadcrumbs, .breadcrumb, .cta, .cta-band, .flash, .alert, .berrors');
    let parts = [...container.children].filter(eligible);
    for (let depth = 0; parts.length === 1 && depth < 3 && !parts[0].matches('p, article') && !parts[0].querySelector('form'); depth++) {
      container = parts[0]; parts = [...container.children].filter(eligible);
    }
    placementAnchor = parts.length >= 2 ? parts[Math.floor((parts.length - 1) / 2)] : container;
    position = parts.length >= 2 ? 'afterend' : 'beforeend';
  }
  if (placementAnchor && !section.contains(placementAnchor)) {
    placementAnchor.insertAdjacentElement(position, section);
    section.dataset.sksPlaced = '1';
  }
  const heading = section.querySelector('.sks-partners__heading');
  if (heading) heading.textContent = "\u041d\u0430\u0448\u0438 \u043f\u0430\u0440\u0442\u043d\u0451\u0440\u044b";
  section.dataset.sksReady = '1';
  const remote = document.createElement('script');
  remote.src = section.dataset.sksSrc;
  section.after(remote);

  let slides = [];
  let index = 0;
  let timer;
  const stop = () => { clearTimeout(timer); timer = undefined; };
  const motion = matchMedia('(prefers-reduced-motion: reduce)');
  const paused = () => document.hidden || motion.matches || section.matches(':hover') || section.contains(document.activeElement);
  function show(next) {
    index = next;
    slides.forEach((slide, i) => {
      const inactive = i !== index;
      slide.classList.toggle('sks-partners__slide--inactive', inactive);
      slide.inert = inactive;
      if (inactive) slide.setAttribute('aria-hidden', 'true');
      else slide.removeAttribute('aria-hidden');
    });
  }
  function schedule() {
    stop();
    if (slides.length < 2 || paused() || !section.isConnected) return;
    timer = setTimeout(() => {
      if (section.isConnected && !paused()) show((index + 1) % slides.length);
      schedule();
    }, 8000);
  }
  const update = new MutationObserver(refresh);
  const removal = new MutationObserver(() => {
    if (section.isConnected) return;
    stop();
    update.disconnect();
    removal.disconnect();
    document.removeEventListener('visibilitychange', schedule);
    motion.removeEventListener('change', schedule);
  });
  function refresh() {
    stop();
    update.disconnect();
    // SKS emits [before], anchor, [after]. Move the original nodes to retain attributes.
    const nodes = Array.from(links.children).flatMap(el =>
      el.classList.contains('sks-partners__slide') ? Array.from(el.children) : [el]);
    slides = [];
    let before;
    for (let i = 0; i < nodes.length; i++) {
      const node = nodes[i];
      if (node.matches('.exchange-before')) { before = node; continue; }
      if (!node.matches('a[href]')) { before = undefined; continue; }
      const slide = document.createElement('div');
      slide.className = 'sks-partners__slide';
      if (before) slide.append(before, ' ');
      before = undefined;
      slide.append(node);
      if (nodes[i + 1]?.matches('.exchange-after')) slide.append(' ', nodes[++i]);
      slides.push(slide);
    }
    links.replaceChildren(...slides);
    section.hidden = slides.length === 0;
    show(0);
    update.observe(links, { childList: true, subtree: true, characterData: true });
    schedule();
  }
  section.addEventListener('pointerenter', stop);
  section.addEventListener('pointerleave', schedule);
  section.addEventListener('focusin', stop);
  section.addEventListener('focusout', () => queueMicrotask(schedule));
  document.addEventListener('visibilitychange', schedule);
  motion.addEventListener('change', schedule);
  removal.observe(document.documentElement, { childList: true, subtree: true });
  refresh();
})();
