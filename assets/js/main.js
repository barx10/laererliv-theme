document.addEventListener('DOMContentLoaded', function () {

  // ========================================
  // HEADER SCROLL SHADOW
  // ========================================
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      header.classList.toggle('scrolled', window.scrollY > 10);
    });
  }

  // ========================================
  // MOBILE MENU TOGGLE
  // ========================================
  var menuToggle = document.querySelector('.menu-toggle');
  var mainNav = document.querySelector('nav.main-nav');
  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', function () {
      menuToggle.classList.toggle('open');
      mainNav.classList.toggle('open');
    });
  }

  // ========================================
  // REVEAL ON SCROLL
  // ========================================
  var reveals = document.querySelectorAll('.reveal');
  if (reveals.length) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          observer.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });
    reveals.forEach(function (el) { observer.observe(el); });
  }

  // ========================================
  // FILTER BAR (nedlastninger, apper, etc.)
  // ========================================
  var filterBtns = document.querySelectorAll('.filter-btn');
  if (filterBtns.length) {
    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var parent = btn.closest('.filter-bar');
        parent.querySelectorAll('.filter-btn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');

        var filter = btn.getAttribute('data-filter');
        var section = parent.nextElementSibling;
        if (!section) return;

        var items = section.querySelectorAll('[data-category]');
        items.forEach(function (item, i) {
          var matches = filter === 'alle' || item.getAttribute('data-category') === filter;
          if (matches) {
            item.classList.remove('hiding');
            item.style.transitionDelay = (i * 0.04) + 's';
          } else {
            item.classList.add('hiding');
            item.style.transitionDelay = '0s';
          }
        });
      });
    });
  }

  // ========================================
  // READ PROGRESS BAR (single.php)
  // ========================================
  var progressBar = document.getElementById('read-progress');
  var articleBody = document.querySelector('.article-body');
  if (progressBar && articleBody) {
    window.addEventListener('scroll', function () {
      var rect = articleBody.getBoundingClientRect();
      var articleTop = rect.top + window.scrollY - 200;
      var articleHeight = articleBody.offsetHeight;
      var scrolled = window.scrollY - articleTop;
      var progress = Math.min(100, Math.max(0, (scrolled / articleHeight) * 100));
      progressBar.style.width = progress + '%';
    });
  }

  // ========================================
  // COPY LINK BUTTON (single.php)
  // ========================================
  var copyBtn = document.querySelector('.share-copy');
  if (copyBtn) {
    copyBtn.addEventListener('click', function (e) {
      e.preventDefault();
      navigator.clipboard.writeText(window.location.href).then(function () {
        var original = copyBtn.textContent;
        copyBtn.textContent = '✓ Kopiert!';
        setTimeout(function () { copyBtn.textContent = original; }, 2000);
      });
    });
  }

  // ========================================
  // YEAR WHEEL (arkiv)
  // ========================================
  var wheel = document.getElementById('year-wheel');
  if (!wheel) return;

  var mask = wheel.closest('.year-wheel-mask');
  var buttons = wheel.querySelectorAll('.year-btn');
  var items = Array.from(buttons);
  var postItems = document.querySelectorAll('.archive-post-item');
  var currentIndex = 0;
  var itemH = 60;
  var dragging = false;
  var startY = 0;
  var startOffset = 0;
  var dragOffset = 0;

  // Beregn topOffset slik at valgt element havner i highlight-sonen (90px fra topp = 1.5 * itemH)
  function getTranslateY(index) {
    return -index * itemH;
  }

  function selectYear(index, animate) {
    if (index < 0) index = 0;
    if (index >= items.length) index = items.length - 1;
    currentIndex = index;

    if (animate !== false) {
      wheel.style.transition = 'transform 0.35s cubic-bezier(0.23, 1, 0.32, 1)';
    } else {
      wheel.style.transition = 'none';
    }

    // Padding: 1.5 items fra topp slik at aktiv lander i highlight
    wheel.style.transform = 'translateY(' + (itemH * 1.5 + getTranslateY(index)) + 'px)';

    items.forEach(function (btn, i) {
      btn.classList.toggle('active', i === index);
    });

    // Filtrer innlegg
    var selectedYear = items[index].getAttribute('data-year');
    postItems.forEach(function (item) {
      var show = item.getAttribute('data-year') === selectedYear;
      item.style.display = show ? '' : 'none';
      if (show) {
        // Trigger animasjon
        item.classList.remove('vis');
        void item.offsetWidth;
        item.classList.add('vis');
      }
    });
  }

  // Klikk på årstall
  items.forEach(function (btn, i) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      selectYear(i);
    });
  });

  // Mouse drag
  mask.addEventListener('mousedown', function (e) {
    dragging = true;
    startY = e.clientY;
    startOffset = currentIndex * itemH;
    wheel.style.transition = 'none';
    e.preventDefault();
  });

  document.addEventListener('mousemove', function (e) {
    if (!dragging) return;
    dragOffset = startY - e.clientY;
  });

  document.addEventListener('mouseup', function () {
    if (!dragging) return;
    dragging = false;
    var newIndex = Math.round((startOffset + dragOffset) / itemH);
    selectYear(newIndex);
    dragOffset = 0;
  });

  // Touch drag
  mask.addEventListener('touchstart', function (e) {
    dragging = true;
    startY = e.touches[0].clientY;
    startOffset = currentIndex * itemH;
    wheel.style.transition = 'none';
  }, { passive: true });

  mask.addEventListener('touchmove', function (e) {
    if (!dragging) return;
    dragOffset = startY - e.touches[0].clientY;
    var offset = itemH * 1.5 - (startOffset + dragOffset);
    wheel.style.transform = 'translateY(' + offset + 'px)';
  }, { passive: true });

  mask.addEventListener('touchend', function () {
    if (!dragging) return;
    dragging = false;
    var newIndex = Math.round((startOffset + dragOffset) / itemH);
    selectYear(newIndex);
    dragOffset = 0;
  });

  // Scroll wheel
  mask.addEventListener('wheel', function (e) {
    e.preventDefault();
    if (Math.abs(e.deltaY) < 50) return;
    var direction = e.deltaY > 0 ? 1 : -1;
    selectYear(currentIndex + direction);
  }, { passive: false });

  // Init: velg første år
  selectYear(0, false);

});
