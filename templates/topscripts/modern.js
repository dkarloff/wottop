document.addEventListener('DOMContentLoaded', () => {
  const path = window.location.pathname.replace(/\/$/, '') || '/';
  document.querySelectorAll('[data-nav]').forEach(link => {
    if ((new URL(link.href).pathname.replace(/\/$/, '') || '/') === path && !window.location.search) {
      link.classList.add('active'); link.setAttribute('aria-current', 'page');
    }
  });
  const menu = document.querySelector('.menu-toggle');
  menu?.addEventListener('click', () => {
    const expanded = menu.getAttribute('aria-expanded') !== 'true';
    menu.setAttribute('aria-expanded', String(expanded));
    document.querySelector('.sidebar').classList.toggle('is-open', expanded);
  });
  const dialog = document.querySelector('#login-dialog');
  document.querySelector('[data-login-open]')?.addEventListener('click', () => dialog.showModal());
  document.querySelector('[data-login-close]')?.addEventListener('click', () => dialog.close());
  dialog?.addEventListener('click', event => { if (event.target === dialog) { const rect = dialog.getBoundingClientRect(); if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) dialog.close(); } });
});
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-copy-code]').forEach(button => {
    button.addEventListener('click', async () => {
      const code = button.dataset.copyCode;
      const status = button.closest('article').querySelector('.copy-status');
      try {
        if (window.isSecureContext && navigator.clipboard) await navigator.clipboard.writeText(code);
        else {
          const field = document.createElement('textarea');
          field.value = code; field.setAttribute('readonly', '');
          field.style.position = 'fixed'; field.style.opacity = '0';
          document.body.appendChild(field); field.select();
          let copied;
          try { copied = document.execCommand('copy'); } finally { field.remove(); button.focus(); }
          if (!copied) throw new Error('Clipboard unavailable');
        }
        status.textContent = 'Код ' + code + ' скопирован';
      } catch { status.textContent = 'Выделите код в заголовке и скопируйте его вручную.'; }
    });
  });
  document.querySelectorAll('[data-expires]').forEach(badge => {
    if (Date.now() > Date.parse(badge.dataset.expires)) {
      badge.textContent = 'Срок действия истёк'; badge.classList.add('expired');
    }
  });
  const search = document.querySelector('#promo-search');
  if (search) {
    const cards = [...document.querySelectorAll('.promo-card')];
    const update = () => {
      const query = search.value.trim().toLocaleLowerCase('ru');
      cards.forEach(card => { card.hidden = !card.textContent.toLocaleLowerCase('ru').includes(query); });
      const count = cards.filter(card => !card.hidden).length;
      document.querySelector('#promo-results').textContent = count ? 'Показано кодов: ' + count : 'Ничего не найдено. Попробуйте другой код или награду.';
    };
    search.addEventListener('input', update); update();
  }
});
