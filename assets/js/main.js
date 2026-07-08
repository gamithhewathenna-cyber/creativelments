/* Creative Elements — Main JS */

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
  '.service-card, .portfolio-item, .testimonial-card, .blog-card, .stat-num'
).forEach((el, i) => {
  el.classList.add('reveal');
  el.style.transitionDelay = (i % 4) * 80 + 'ms';
  observer.observe(el);
});
