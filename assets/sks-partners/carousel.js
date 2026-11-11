(() => {
  'use strict';
  const section = document.querySelector('[data-sks-partners]');
  const links = section?.querySelector('#exchange-links');
  if (!links || section.dataset.sksReady) return;
  if (location.pathname !== '/' || location.search) { section.remove(); return; }
  // Place this site's block between complete content sections before revealing links.
  const placementAnchor = document.querySelector("#materials #dle-content > article:nth-of-type(4)");
  if (placementAnchor && !section.contains(placementAnchor)) {
    placementAnchor.insertAdjacentElement("afterend", section);
    section.dataset.sksPlaced = '1';
  }
  const heading = section.querySelector('.sks-partners__heading');
  if (heading) heading.textContent = "Наши партнёры";
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
