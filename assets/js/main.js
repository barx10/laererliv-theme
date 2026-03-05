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
  // DOWNLOAD ACCORDION (nedlastninger)
  // ========================================
  var dlHeaders = document.querySelectorAll('.download-header');
  if (dlHeaders.length) {
    dlHeaders.forEach(function (header) {
      header.addEventListener('click', function () {
        var item = header.closest('.download-item');
        var panel = item.querySelector('.download-panel');
        var isOpen = item.classList.contains('open');

        // Close all others
        document.querySelectorAll('.download-item.open').forEach(function (openItem) {
          if (openItem !== item) {
            openItem.classList.remove('open');
            openItem.querySelector('.download-header').setAttribute('aria-expanded', 'false');
            openItem.querySelector('.download-panel').setAttribute('aria-hidden', 'true');
          }
        });

        // Toggle this one
        item.classList.toggle('open', !isOpen);
        header.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
        panel.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
      });
    });
  }

  // ========================================
  // PODCAST CAROUSEL (publikasjoner)
  // ========================================
  var carousel = document.getElementById('podcast-carousel');
  if (carousel) {
    var track = carousel.querySelector('.podcast-carousel-track');
    // Find all embed wrappers (wp-block-embed) or standalone iframes
    var embeds = track.querySelectorAll('.wp-block-embed, figure.wp-block-embed, iframe');
    var slides = [];

    if (embeds.length > 0) {
      // Group: each embed + any preceding siblings (headings, paragraphs) = one slide
      var children = Array.from(track.children);
      var groups = [];
      var currentGroup = [];

      children.forEach(function (child) {
        var isEmbed = child.classList.contains('wp-block-embed') || child.tagName === 'IFRAME';
        if (isEmbed) {
          currentGroup.push(child);
          groups.push(currentGroup);
          currentGroup = [];
        } else {
          currentGroup.push(child);
        }
      });
      // Leftover non-embed nodes at the end (unlikely but safe)
      if (currentGroup.length) groups.push(currentGroup);

      // Wrap each group in a podcast-slide div
      track.innerHTML = '';
      groups.forEach(function (group, i) {
        var slide = document.createElement('div');
        slide.className = 'podcast-slide' + (i === 0 ? ' active' : '');
        slide.setAttribute('data-slide', i);
        group.forEach(function (el) { slide.appendChild(el); });
        track.appendChild(slide);
      });

      slides = track.querySelectorAll('.podcast-slide');
    }

    var prevBtn = carousel.querySelector('.podcast-prev');
    var nextBtn = carousel.querySelector('.podcast-next');
    var counter = document.getElementById('podcast-current');
    var total = document.getElementById('podcast-total');
    var current = 0;

    if (total) total.textContent = slides.length;
    if (slides.length <= 1) {
      var nav = carousel.querySelector('.podcast-nav');
      if (nav) nav.style.display = 'none';
    }

    function showSlide(index) {
      slides.forEach(function (s) { s.classList.remove('active'); });
      slides[index].classList.add('active');
      current = index;
      if (counter) counter.textContent = index + 1;
      if (prevBtn) prevBtn.disabled = index === 0;
      if (nextBtn) nextBtn.disabled = index === slides.length - 1;
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        if (current > 0) showSlide(current - 1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        if (current < slides.length - 1) showSlide(current + 1);
      });
    }
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
  // APP PAGINERING (apper, 3 og 3)
  // ========================================
  var appsPagNav = document.getElementById('apps-pagination-nav');
  if (appsPagNav) {
    var appsList = document.querySelector('.apps-list');
    var appsPrev = document.getElementById('apps-prev');
    var appsNext = document.getElementById('apps-next');
    var appsPageCurrent = document.getElementById('apps-page-current');
    var appsPageTotal = document.getElementById('apps-page-total');
    var appsPerPage = 3;
    var appsCurrentPage = 0;

    function getVisibleApps() {
      return Array.from(appsList.querySelectorAll('.app-item:not(.hiding)'));
    }

    function showAppsPage(page) {
      var visible = getVisibleApps();
      var totalPages = Math.ceil(visible.length / appsPerPage);
      if (page < 0) page = 0;
      if (page >= totalPages) page = totalPages - 1;
      appsCurrentPage = page;

      visible.forEach(function (item, i) {
        var inPage = i >= page * appsPerPage && i < (page + 1) * appsPerPage;
        item.classList.toggle('page-hidden', !inPage);
      });

      if (appsPageCurrent) appsPageCurrent.textContent = page + 1;
      if (appsPageTotal) appsPageTotal.textContent = totalPages || 1;
      if (appsPrev) appsPrev.disabled = page === 0;
      if (appsNext) appsNext.disabled = page >= totalPages - 1;

      if (totalPages > 1) {
        appsPagNav.style.display = 'flex';
      } else {
        appsPagNav.style.display = 'none';
      }
    }

    if (appsPrev) appsPrev.addEventListener('click', function () { showAppsPage(appsCurrentPage - 1); });
    if (appsNext) appsNext.addEventListener('click', function () { showAppsPage(appsCurrentPage + 1); });

    // Reset til side 1 når filter klikkes
    document.querySelectorAll('.filter-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        setTimeout(function () { showAppsPage(0); }, 0);
      });
    });

    showAppsPage(0);
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

  // Year arrow buttons
  var arrowUp = document.querySelector('.year-arrow--up');
  var arrowDown = document.querySelector('.year-arrow--down');

  function updateArrows() {
    if (arrowUp) arrowUp.disabled = currentIndex === 0;
    if (arrowDown) arrowDown.disabled = currentIndex === items.length - 1;
  }

  var _origSelectYear = selectYear;
  selectYear = function(index, animate) {
    _origSelectYear(index, animate);
    updateArrows();
  };

  if (arrowUp) {
    arrowUp.addEventListener('click', function () {
      if (currentIndex > 0) selectYear(currentIndex - 1);
    });
  }
  if (arrowDown) {
    arrowDown.addEventListener('click', function () {
      if (currentIndex < items.length - 1) selectYear(currentIndex + 1);
    });
  }

  // Init: velg første år
  selectYear(0, false);

});
