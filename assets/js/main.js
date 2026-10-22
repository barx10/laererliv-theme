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
  // PODKAST: avspilleren lastes først ved klikk (publikasjoner)
  // ========================================
  var podcastBtns = document.querySelectorAll('.podcast-play');
  if (podcastBtns.length) {
    var stopEpisode = function (btn) {
      var item = btn.closest('.podcast-item');
      item.classList.remove('playing');
      item.querySelector('.podcast-player').replaceChildren();
      btn.setAttribute('aria-expanded', 'false');
      btn.innerHTML = '&#9654;';
    };

    podcastBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var item = btn.closest('.podcast-item');
        if (item.classList.contains('playing')) { stopEpisode(btn); return; }

        // Bare én avspiller om gangen
        podcastBtns.forEach(function (other) {
          if (other !== btn && other.closest('.podcast-item').classList.contains('playing')) stopEpisode(other);
        });

        var iframe = document.createElement('iframe');
        iframe.src = btn.dataset.embedSrc;
        iframe.title = btn.dataset.embedTitle || 'Podkast';
        iframe.allow = 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture';
        iframe.height = 152;
        iframe.loading = 'eager';
        item.querySelector('.podcast-player').replaceChildren(iframe);
        item.classList.add('playing');
        btn.setAttribute('aria-expanded', 'true');
        btn.innerHTML = '&#10005;';
      });
    });
  }

  // Skjul årsgrupper uten treff når publikasjoner filtreres
  var pubGroups = document.querySelectorAll('.pub-year-group');
  if (pubGroups.length) {
    document.querySelectorAll('.filter-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        setTimeout(function () {
          pubGroups.forEach(function (group) {
            group.hidden = !group.querySelector('.pub-item:not(.hiding)');
          });
        }, 0);
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
  // APPER: VIS FLERE (de første 9, resten bak knapp)
  // ========================================
  var appsMore = document.querySelector('.apps-more');
  if (appsMore) {
    var appsList = document.querySelector('.apps-list');
    var appsLimit = 9;
    var appsExpanded = false;

    function applyAppsLimit() {
      var visible = Array.from(appsList.querySelectorAll('.app-item:not(.hiding)'));
      visible.forEach(function (item, i) {
        item.classList.toggle('over-limit', !appsExpanded && i >= appsLimit);
      });
      appsMore.hidden = appsExpanded || visible.length <= appsLimit;
    }

    appsMore.addEventListener('click', function () {
      appsExpanded = true;
      applyAppsLimit();
    });

    // Filteret setter .hiding først, så teller vi på nytt
    document.querySelectorAll('.filter-btn').forEach(function (btn) {
      btn.addEventListener('click', function () { setTimeout(applyAppsLimit, 0); });
    });

    applyAppsLimit();
  }

  // ========================================
  // APP-VIDEO (YouTube i dialog)
  // ========================================
  const videoModal = document.getElementById('video-modal');
  if (videoModal) {
    const videoFrame = videoModal.querySelector('.video-modal-frame');

    document.querySelectorAll('.app-video-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const iframe = document.createElement('iframe');
        iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(btn.dataset.videoId) + '?autoplay=1&rel=0';
        iframe.title = btn.dataset.videoTitle || 'Video';
        iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
        iframe.allowFullscreen = true;
        videoFrame.replaceChildren(iframe);
        videoModal.showModal();
      });
    });

    videoModal.querySelector('.video-modal-close').addEventListener('click', () => videoModal.close());
    videoModal.addEventListener('click', e => { if (e.target === videoModal) videoModal.close(); });
    videoModal.addEventListener('close', () => videoFrame.replaceChildren());
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
