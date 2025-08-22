// script.js (clean single version)
document.addEventListener('DOMContentLoaded', () => {
  // DARK / LIGHT MODE
  const modeBtn = document.getElementById('modeToggle');
  if (localStorage.getItem('mode') === 'dark') document.body.classList.add('dark-mode');

  if (modeBtn) {
    modeBtn.addEventListener('click', () => {
      const isDark = document.body.classList.toggle('dark-mode');
      localStorage.setItem('mode', isDark ? 'dark' : 'light');
    });
  }

  // NAV / BURGER (works with #burgerBtn or .burger)
  const burgerBtn = document.getElementById('burgerBtn') || document.querySelector('.burger');
  const navLinks = document.querySelector('.nav-links');
  const topnav = document.querySelector('.topnav');

  function closeNav() {
    if (navLinks && navLinks.classList.contains('open')) {
      navLinks.classList.remove('open');
      if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'false');
    }
  }

  function openNav() {
    if (navLinks) {
      navLinks.classList.add('open');
      if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'true');
    }
  }

  if (burgerBtn && navLinks) {
    // ensure accessibility attribute exists
    if (!burgerBtn.hasAttribute('aria-expanded')) burgerBtn.setAttribute('aria-expanded', 'false');

    burgerBtn.addEventListener('click', (e) => {
      const expanded = burgerBtn.getAttribute('aria-expanded') === 'true';
      if (expanded) closeNav(); else openNav();
      e.stopPropagation();
    });

    // close when clicking a nav link (mobile)
    navLinks.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        // on small screens close after click
        if (window.innerWidth <= 860) closeNav();
      });
    });
  }

  // Close nav when clicking outside
  document.addEventListener('click', (e) => {
    const target = e.target;
    if (!target.closest('.topnav')) {
      closeNav();
    }
  });

  // Close nav on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeNav();
  });

  // TIMER HELPER (single implementation)
  window.startExamTimer = function(seconds, displayEl, onExpire) {
    let s = Number(seconds) || 0;
    const el = document.querySelector(displayEl);
    if (!el) return;

    function fmt(t) {
      const m = Math.floor(t / 60);
      const sec = t % 60;
      return `${m.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
    }

    el.textContent = fmt(s);
    const id = setInterval(() => {
      s--;
      if (s < 0) {
        clearInterval(id);
        if (typeof onExpire === 'function') onExpire();
        return;
      }
      el.textContent = fmt(s);
    }, 1000);
    return id; // return interval id if caller wants to clear()
  };
});
