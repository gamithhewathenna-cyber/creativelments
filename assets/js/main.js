/* Creative Elements — Main JS */

// ---- Page transition (fade out on navigate; arrival fade-in is pure CSS) ----
document.addEventListener('click', (e) => {
  const link = e.target.closest('a');
  if (!link) return;

  const href = link.getAttribute('href');
  if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
  if (link.target === '_blank' || link.hasAttribute('download')) return;
  if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;

  let url;
  try { url = new URL(href, window.location.href); } catch { return; }
  if (url.origin !== window.location.origin) return;
  if (url.href === window.location.href) return;

  e.preventDefault();
  document.body.classList.add('page-exiting');
  setTimeout(() => { window.location.href = url.href; }, 280);
});

// ---- Custom cursor (desktop with a real mouse only) ----
if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
  const cursorDot  = document.getElementById('cursorDot');
  const cursorRing = document.getElementById('cursorRing');
  if (cursorDot && cursorRing) {
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let ringX = mouseX;
    let ringY = mouseY;

    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursorDot.style.transform = `translate(${mouseX}px, ${mouseY}px) translate(-50%, -50%)`;
    });

    const animateCursorRing = () => {
      ringX += (mouseX - ringX) * 0.15;
      ringY += (mouseY - ringY) * 0.15;
      cursorRing.style.transform = `translate(${ringX}px, ${ringY}px) translate(-50%, -50%)`;
      requestAnimationFrame(animateCursorRing);
    };
    animateCursorRing();

    document.querySelectorAll('a, button, .filter-btn').forEach((el) => {
      el.addEventListener('mouseenter', () => cursorRing.classList.add('cursor-hover'));
      el.addEventListener('mouseleave', () => cursorRing.classList.remove('cursor-hover'));
    });
  }
}

// ---- Hero image slider (sliding transition) ----
const heroSlides = document.querySelectorAll('.hero-slide');
if (heroSlides.length > 1) {
  const heroPrev = document.querySelector('.hero-arrow-prev');
  const heroNext = document.querySelector('.hero-arrow-next');
  let currentHeroSlide = 0;
  let heroInterval;

  const showHeroSlide = (targetIndex, direction = 1) => {
    const newIndex = (targetIndex + heroSlides.length) % heroSlides.length;
    if (newIndex === currentHeroSlide) return;
    const oldSlide = heroSlides[currentHeroSlide];
    const newSlide = heroSlides[newIndex];

    // Park the incoming slide on the correct side before revealing it
    newSlide.style.transition = 'none';
    newSlide.style.transform = direction > 0 ? 'translateX(100%)' : 'translateX(-100%)';
    newSlide.classList.add('active');
    void newSlide.offsetHeight; // force reflow so the browser registers the starting position
    newSlide.style.transition = '';
    newSlide.style.transform = 'translateX(0)';

    oldSlide.style.transform = direction > 0 ? 'translateX(-100%)' : 'translateX(100%)';
    oldSlide.classList.remove('active');

    currentHeroSlide = newIndex;
  };

  const startHeroAutoplay = () => {
    heroInterval = setInterval(() => showHeroSlide(currentHeroSlide + 1, 1), 5000);
  };
  const resetHeroAutoplay = () => {
    clearInterval(heroInterval);
    startHeroAutoplay();
  };

  startHeroAutoplay();

  if (heroPrev) heroPrev.addEventListener('click', () => { showHeroSlide(currentHeroSlide - 1, -1); resetHeroAutoplay(); });
  if (heroNext) heroNext.addEventListener('click', () => { showHeroSlide(currentHeroSlide + 1, 1); resetHeroAutoplay(); });
}

// ---- Recent Projects arrow navigation ----
const portfolioScroll = document.getElementById('portfolioScroll');
const portfolioPrev   = document.querySelector('.portfolio-arrow-prev');
const portfolioNext   = document.querySelector('.portfolio-arrow-next');
if (portfolioScroll && portfolioPrev && portfolioNext) {
  const scrollStep = () => {
    const slide = portfolioScroll.querySelector('.portfolio-slide');
    return slide ? slide.offsetWidth + 24 : 340;
  };
  portfolioPrev.addEventListener('click', () => portfolioScroll.scrollBy({ left: -scrollStep(), behavior: 'smooth' }));
  portfolioNext.addEventListener('click', () => portfolioScroll.scrollBy({ left: scrollStep(), behavior: 'smooth' }));
}

// ---- Client Logos auto-scrolling marquee + arrows ----
const logosScroll = document.getElementById('logosScroll');
if (logosScroll) {
  const logosPrev = document.querySelector('.logos-arrow-prev');
  const logosNext = document.querySelector('.logos-arrow-next');

  const logoStep = () => {
    const item = logosScroll.querySelector('.logos-item');
    return item ? item.offsetWidth + 48 : 180;
  };

  // The logo list is rendered twice back-to-back, so the halfway point of the
  // scrollable width is an identical match to the start — wrapping there is seamless.
  const wrapLogosScroll = () => {
    const half = logosScroll.scrollWidth / 2;
    if (logosScroll.scrollLeft >= half) logosScroll.scrollLeft -= half;
    else if (logosScroll.scrollLeft <= 0) logosScroll.scrollLeft += half;
  };

  let logosAutoTimer;
  const startLogosAuto = () => {
    logosAutoTimer = setInterval(() => {
      logosScroll.scrollLeft += 1;
      wrapLogosScroll();
    }, 25);
  };
  const stopLogosAuto = () => clearInterval(logosAutoTimer);

  // Pause auto-scroll during a manual arrow click and resume only after the
  // smooth-scroll animation finishes — restarting immediately would set
  // scrollLeft directly on the very next tick and cancel the animation.
  let logosResumeTimer;
  const pauseLogosAuto = () => {
    stopLogosAuto();
    clearTimeout(logosResumeTimer);
    logosResumeTimer = setTimeout(startLogosAuto, 700);
  };

  startLogosAuto();
  logosScroll.addEventListener('mouseenter', stopLogosAuto);
  logosScroll.addEventListener('mouseleave', () => {
    clearTimeout(logosResumeTimer);
    startLogosAuto();
  });

  if (logosPrev) logosPrev.addEventListener('click', () => {
    pauseLogosAuto();
    logosScroll.scrollBy({ left: -logoStep(), behavior: 'smooth' });
  });
  if (logosNext) logosNext.addEventListener('click', () => {
    pauseLogosAuto();
    logosScroll.scrollBy({ left: logoStep(), behavior: 'smooth' });
  });
}

// ---- Blog post: copy link share button ----
const blogShareCopy = document.querySelector('.blog-share-copy');
if (blogShareCopy) {
  blogShareCopy.addEventListener('click', () => {
    navigator.clipboard.writeText(blogShareCopy.dataset.url).then(() => {
      blogShareCopy.classList.add('copied');
      setTimeout(() => blogShareCopy.classList.remove('copied'), 1500);
    });
  });
}

// ---- Project detail popup ----
const projectModal = document.getElementById('projectModal');
if (projectModal) {
  const modalTitle   = document.getElementById('projectModalTitle');
  const modalDesc    = document.getElementById('projectModalDesc');
  const modalGallery = document.getElementById('projectModalGallery');
  const modalLink    = document.getElementById('projectModalLink');
  const modalClose   = document.getElementById('projectModalClose');

  const openProjectModal = (trigger) => {
    modalTitle.textContent = trigger.dataset.title || '';
    modalDesc.textContent  = trigger.dataset.desc || '';
    modalGallery.innerHTML = '';
    let imageCount = 0;
    ['img1', 'img2', 'img3'].forEach((key) => {
      const src = trigger.dataset[key];
      if (!src) return;
      const wrap = document.createElement('div');
      wrap.className = 'project-modal-img-wrap';
      const img = document.createElement('img');
      img.src = src;
      img.alt = trigger.dataset.title || '';
      wrap.appendChild(img);
      modalGallery.appendChild(wrap);
      imageCount++;
    });
    modalGallery.classList.toggle('single-image', imageCount === 1);

    if (trigger.dataset.link) {
      modalLink.href = trigger.dataset.link;
      modalLink.style.display = 'inline-flex';
    } else {
      modalLink.style.display = 'none';
    }

    projectModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  };

  const closeProjectModal = () => {
    projectModal.classList.remove('open');
    document.body.style.overflow = '';
  };

  document.querySelectorAll('.project-trigger').forEach((trigger) => {
    trigger.addEventListener('click', () => openProjectModal(trigger));
  });

  modalClose.addEventListener('click', closeProjectModal);
  projectModal.addEventListener('click', (e) => { if (e.target === projectModal) closeProjectModal(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeProjectModal(); });
}

// ---- Floating Contact button popup ----
const contactModal    = document.getElementById('contactModal');
const contactFloatBtn = document.getElementById('contactFloatBtn');
if (contactModal && contactFloatBtn) {
  const contactModalClose = document.getElementById('contactModalClose');

  const openContactModal = () => {
    contactModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  const closeContactModal = () => {
    contactModal.classList.remove('open');
    document.body.style.overflow = '';
  };

  contactFloatBtn.addEventListener('click', openContactModal);
  contactModalClose.addEventListener('click', closeContactModal);
  contactModal.addEventListener('click', (e) => { if (e.target === contactModal) closeContactModal(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeContactModal(); });
}

// ---- Reviews arrow navigation ----
const reviewsScroll = document.getElementById('reviewsScroll');
const reviewsPrev   = document.querySelector('.review-arrow-prev');
const reviewsNext   = document.querySelector('.review-arrow-next');
if (reviewsScroll && reviewsPrev && reviewsNext) {
  const reviewStep = () => {
    const card = reviewsScroll.querySelector('.testimonial-card');
    return card ? card.offsetWidth + 24 : 300;
  };
  reviewsPrev.addEventListener('click', () => reviewsScroll.scrollBy({ left: -reviewStep(), behavior: 'smooth' }));
  reviewsNext.addEventListener('click', () => reviewsScroll.scrollBy({ left: reviewStep(), behavior: 'smooth' }));
}

// ---- Reviews "Read more" toggle ----
document.querySelectorAll('.review-more').forEach((btn) => {
  btn.addEventListener('click', () => {
    const text = btn.previousElementSibling;
    const expanded = text.classList.toggle('expanded');
    btn.textContent = expanded ? 'Read less' : 'Read more';
  });
});

// ---- Navbar scroll effect ----
const navbar = document.getElementById('navbar');
if (navbar) {
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 40);
  });
}

// ---- Mobile menu toggle ----
const navToggle = document.getElementById('navToggle');
const navMenu   = document.getElementById('navMenu');
if (navToggle && navMenu) {
  navToggle.addEventListener('click', () => {
    navMenu.classList.toggle('open');
  });
  // Close on outside click
  document.addEventListener('click', (e) => {
    if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
      navMenu.classList.remove('open');
    }
  });
}

// ---- Animated counter ----
function animateCounter(el) {
  const target = parseInt(el.dataset.target, 10);
  const suffix = el.dataset.suffix || '';
  const duration = 1800;
  const start = performance.now();
  const update = (now) => {
    const progress = Math.min((now - start) / duration, 1);
    const ease = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.round(ease * target) + suffix;
    if (progress < 1) requestAnimationFrame(update);
  };
  requestAnimationFrame(update);
}

// ---- Intersection Observer for counters & reveals ----
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      if (entry.target.classList.contains('counter')) {
        animateCounter(entry.target);
      }
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.counter, .reveal').forEach(el => observer.observe(el));

// ---- AJAX Contact Form ----
const contactForm = document.getElementById('contactForm');
if (contactForm) {
  contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = contactForm.querySelector('[type=submit]');
    const success = document.getElementById('formSuccess');
    const error   = document.getElementById('formError');
    btn.disabled = true;
    btn.textContent = 'Sending…';
    success.style.display = 'none';
    error.style.display   = 'none';

    try {
      const res  = await fetch('/contact-handler.php', {
        method: 'POST',
        body: new FormData(contactForm),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      if (data.ok) {
        success.style.display = 'block';
        contactForm.reset();
      } else {
        error.textContent = data.message || 'Something went wrong. Please try again.';
        error.style.display = 'block';
      }
    } catch {
      error.textContent = 'Network error. Please try again.';
      error.style.display = 'block';
    } finally {
      btn.disabled = false;
      btn.textContent = 'Send Message';
    }
  });
}

// ---- Reveal animations ----
const style = document.createElement('style');
style.textContent = `
  .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
  .reveal.visible { opacity: 1; transform: none; }
`;
document.head.appendChild(style);

document.querySelectorAll(
  '.service-card, .testimonial-card, .blog-card, .stat-num'
).forEach((el, i) => {
  el.classList.add('reveal');
  el.style.transitionDelay = (i % 4) * 80 + 'ms';
  observer.observe(el);
});

// ---- Section reveal animations (fade / pop / slide, cycling per section) ----
const sectionStyle = document.createElement('style');
sectionStyle.textContent = `
  .section-reveal { opacity: 0; transition: opacity .8s cubic-bezier(.4,0,.2,1), transform .8s cubic-bezier(.4,0,.2,1); }
  .section-reveal.visible { opacity: 1; transform: none !important; }
  .section-reveal-up { transform: translateY(50px); }
  .section-reveal-pop { transform: scale(.94); }
  .section-reveal-left { transform: translateX(-60px); }
  .section-reveal-right { transform: translateX(60px); }
`;
document.head.appendChild(sectionStyle);

const sectionEffects = ['section-reveal-up', 'section-reveal-pop', 'section-reveal-left', 'section-reveal-right'];
const noRevealSections = ['.hero', '.page-hero', '.cta-banner', '.no-reveal'];
let sectionEffectIndex = 0;
document.querySelectorAll('section').forEach((section) => {
  if (noRevealSections.some((sel) => section.matches(sel))) return;
  section.classList.add('section-reveal', sectionEffects[sectionEffectIndex % sectionEffects.length]);
  sectionEffectIndex++;
  observer.observe(section);
});
