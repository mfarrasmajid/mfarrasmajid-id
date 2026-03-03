/* ============================================
   M. Farras Majid - Portfolio JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ---------- Loader ----------
  const loader = document.getElementById('loader');
  if (loader) {
    window.addEventListener('load', () => {
      setTimeout(() => {
        loader.classList.add('hidden');
      }, 500);
    });
    // Fallback: hide loader after 3s
    setTimeout(() => {
      loader.classList.add('hidden');
    }, 3000);
  }

  // ---------- Navbar Scroll Effect ----------
  const navbar = document.getElementById('navbar');
  const backToTop = document.getElementById('backToTop');

  function handleScroll() {
    const scrollY = window.scrollY;

    // Navbar
    if (navbar && !navbar.classList.contains('scrolled')) {
      if (scrollY > 50) {
        navbar.classList.add('scrolled');
      }
    } else if (navbar && !document.querySelector('.page-header') && !document.querySelector('.error-page')) {
      if (scrollY <= 50) {
        navbar.classList.remove('scrolled');
      }
    }

    // Back to top button
    if (backToTop) {
      if (scrollY > 400) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    }
  }

  window.addEventListener('scroll', handleScroll);
  handleScroll();

  // Back to top click
  if (backToTop) {
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ---------- Mobile Navigation ----------
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');

  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navToggle.classList.toggle('active');
      navLinks.classList.toggle('active');
    });

    // Close menu when clicking a link
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navToggle.classList.remove('active');
        navLinks.classList.remove('active');
      });
    });

    // Close menu on outside click
    document.addEventListener('click', (e) => {
      if (!navToggle.contains(e.target) && !navLinks.contains(e.target)) {
        navToggle.classList.remove('active');
        navLinks.classList.remove('active');
      }
    });
  }

  // ---------- Active Nav Link Highlight ----------
  const sections = document.querySelectorAll('section[id]');

  function highlightNavLink() {
    const scrollY = window.scrollY + 100;

    sections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      const id = section.getAttribute('id');

      if (scrollY >= top && scrollY < top + height) {
        document.querySelectorAll('.nav-links a').forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${id}`) {
            link.classList.add('active');
          }
        });
      }
    });
  }

  if (sections.length > 0) {
    window.addEventListener('scroll', highlightNavLink);
  }

  // ---------- Typing Effect ----------
  const typingElement = document.getElementById('typingText');
  if (typingElement) {
    const roles = [
      'Web Developer',
      'Data Engineer',
      'DevOps Engineer',
      'Full-Stack Developer',
      'Cloud Architect'
    ];
    let roleIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typingSpeed = 100;

    function typeText() {
      const currentRole = roles[roleIndex];

      if (isDeleting) {
        typingElement.textContent = currentRole.substring(0, charIndex - 1);
        charIndex--;
        typingSpeed = 50;
      } else {
        typingElement.textContent = currentRole.substring(0, charIndex + 1);
        charIndex++;
        typingSpeed = 100;
      }

      if (!isDeleting && charIndex === currentRole.length) {
        typingSpeed = 2000; // Pause at end
        isDeleting = true;
      } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        roleIndex = (roleIndex + 1) % roles.length;
        typingSpeed = 500; // Pause before next word
      }

      setTimeout(typeText, typingSpeed);
    }

    typeText();
  }

  // ---------- Scroll Animations (Intersection Observer) ----------
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');

        // Animate skill bars when skill section is visible
        if (entry.target.closest('#skills') || entry.target.classList.contains('skill-category')) {
          animateSkillBars(entry.target);
        }

        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.fade-in, .fade-in-left, .fade-in-right').forEach(el => {
    observer.observe(el);
  });

  // ---------- Skill Bar Animation ----------
  function animateSkillBars(container) {
    const bars = container.querySelectorAll('.skill-progress');
    bars.forEach(bar => {
      const targetWidth = bar.style.getPropertyValue('--target-width');
      if (targetWidth) {
        setTimeout(() => {
          bar.style.width = targetWidth;
          bar.classList.add('animated');
        }, 200);
      }
    });
  }

  // ---------- Project Filter ----------
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.project-card');
  const blogCards = document.querySelectorAll('.blog-card[data-category]');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Update active filter
      const parent = btn.closest('.projects-filter');
      parent.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.getAttribute('data-filter');

      // Filter project cards
      const cards = document.querySelectorAll('.project-card[data-category], .blog-card[data-category]');
      cards.forEach(card => {
        if (filter === 'all' || card.getAttribute('data-category') === filter) {
          card.style.display = '';
          card.style.animation = 'fadeInUp 0.5s ease forwards';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // Add fadeInUp keyframes dynamically
  if (!document.querySelector('#dynamic-animations')) {
    const style = document.createElement('style');
    style.id = 'dynamic-animations';
    style.textContent = `
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  }

  // ---------- Contact Form Validation ----------
  const contactForm = document.getElementById('contactForm');
  const formMessage = document.getElementById('formMessage');

  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      const name = document.getElementById('name');
      const email = document.getElementById('email');
      const subject = document.getElementById('subject');
      const message = document.getElementById('message');

      // Basic validation
      if (!name.value.trim() || !email.value.trim() || !subject.value || !message.value.trim()) {
        e.preventDefault();
        showFormMessage('Mohon lengkapi semua field yang wajib (*)', 'error');
        return;
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        e.preventDefault();
        showFormMessage('Format email tidak valid', 'error');
        return;
      }

      // Show loading state
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      submitBtn.disabled = true;
    });
  }

  function showFormMessage(text, type) {
    if (formMessage) {
      formMessage.textContent = text;
      formMessage.className = `form-message ${type}`;
      setTimeout(() => {
        formMessage.className = 'form-message';
      }, 5000);
    }
  }

  // ---------- Smooth Scroll for Anchor Links ----------
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return;

      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

  // ---------- Counter Animation ----------
  const counters = document.querySelectorAll('.stat-item h3');

  function animateCounter(el) {
    const text = el.textContent;
    const match = text.match(/(\d+)/);
    if (!match) return;

    const target = parseInt(match[1]);
    const suffix = text.replace(match[1], '');
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;

    function update() {
      current += step;
      if (current >= target) {
        el.textContent = target + suffix;
        return;
      }
      el.textContent = Math.floor(current) + suffix;
      requestAnimationFrame(update);
    }

    update();
  }

  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(counter => counterObserver.observe(counter));

  // ---------- Image Lazy Load Placeholder ----------
  document.querySelectorAll('img').forEach(img => {
    img.addEventListener('error', function() {
      // Create SVG placeholder for missing images
      const width = this.width || 400;
      const height = this.height || 300;
      const text = this.alt || 'Image';
      this.src = `data:image/svg+xml,${encodeURIComponent(`
        <svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
          <rect fill="#1a1a1a" width="${width}" height="${height}"/>
          <rect fill="#e63946" x="${width/2-30}" y="${height/2-30}" width="60" height="60" rx="12" opacity="0.3"/>
          <text fill="#6c6c6c" font-family="Inter,sans-serif" font-size="14" text-anchor="middle" x="${width/2}" y="${height/2+45}">${text.substring(0, 30)}</text>
          <text fill="#e63946" font-family="Inter,sans-serif" font-size="24" text-anchor="middle" x="${width/2}" y="${height/2+5}">📷</text>
        </svg>
      `)}`;
      this.style.objectFit = 'cover';
    });
  });

  // ---------- Keyboard Navigation ----------
  document.addEventListener('keydown', (e) => {
    // ESC to close mobile menu
    if (e.key === 'Escape' && navLinks && navLinks.classList.contains('active')) {
      navToggle.classList.remove('active');
      navLinks.classList.remove('active');
    }
  });

  // ---------- Prefers Reduced Motion ----------
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  if (prefersReducedMotion.matches) {
    document.querySelectorAll('.fade-in, .fade-in-left, .fade-in-right').forEach(el => {
      el.classList.add('visible');
    });
  }

  console.log('%c🚀 M. Farras Majid Portfolio', 'color: #e63946; font-size: 18px; font-weight: bold;');
  console.log('%cBuilt with ❤️ using vanilla HTML, CSS & JavaScript', 'color: #b0b0b0; font-size: 12px;');

});

// ---------- Lightbox ----------
function openLightbox(src, caption) {
  const overlay = document.getElementById('lightboxOverlay');
  const img = document.getElementById('lightboxImg');
  const cap = document.getElementById('lightboxCaption');
  if (!overlay || !img) return;
  img.src = src;
  if (cap) cap.textContent = caption || '';
  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  const overlay = document.getElementById('lightboxOverlay');
  if (!overlay) return;
  overlay.classList.remove('active');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeLightbox();
});
