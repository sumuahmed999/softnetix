// ===================================
// Ultra Smooth Scroll Animations
// ===================================

class ScrollAnimations {
  constructor() {
    this.elements = [];
    this.init();
  }

  init() {
    this.setupSmoothScrolling();
    this.setupScrollAnimations();
    this.bindEvents();
  }

  setupSmoothScrolling() {
    // Ultra smooth scrolling
    document.documentElement.style.scrollBehavior = 'smooth';
    
    let isScrolling = false;
    window.addEventListener('scroll', () => {
      if (!isScrolling) {
        requestAnimationFrame(() => {
          this.handleScroll();
          isScrolling = false;
        });
        isScrolling = true;
      }
    }, { passive: true });
  }

  setupScrollAnimations() {
    // Find all sections and animatable elements
    const animateElements = document.querySelectorAll(`
      section,
      .service-card,
      .portfolio-item,
      .testimonial-card,
      .stats-item,
      .feature-card,
      .process-step,
      .team-member,
      .about-icon-item
    `);

    animateElements.forEach((element) => {
      // Add animation class if not already present
      if (!element.classList.contains('scroll-fade')) {
        element.classList.add('scroll-fade');
      }
      
      this.elements.push({
        element,
        animated: false
      });
    });

    // Initial check
    this.checkAnimations();
  }

  handleScroll() {
    this.checkAnimations();
  }

  checkAnimations() {
    const windowHeight = window.innerHeight;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    this.elements.forEach((item) => {
      if (!item.animated) {
        const rect = item.element.getBoundingClientRect();
        const elementTop = rect.top + scrollTop;
        
        // Trigger when element is 15% visible in viewport
        const triggerPoint = elementTop - windowHeight * 0.85;
        
        if (scrollTop > triggerPoint) {
          this.animateElement(item.element);
          item.animated = true;
        }
      }
    });
  }

  animateElement(element) {
    requestAnimationFrame(() => {
      element.classList.add('visible');
      
      // Stagger child elements
      const children = element.querySelectorAll('.scroll-fade');
      children.forEach((child, index) => {
        setTimeout(() => {
          child.classList.add('visible');
        }, index * 100);
      });
    });
  }

  bindEvents() {
    // Smooth scroll for anchor links
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href^="#"]');
      if (link) {
        e.preventDefault();
        const targetId = link.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          this.smoothScrollTo(targetElement);
        }
      }
    });

    // Recalculate on resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        this.recalculate();
      }, 250);
    });
  }

  smoothScrollTo(element) {
    const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 70;
    const targetPosition = element.offsetTop - navbarHeight - 20;
    
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    const duration = Math.min(Math.abs(distance) * 0.5, 1000);
    let start = null;
    
    const easeInOutCubic = (t) => {
      return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
    };
    
    const animation = (currentTime) => {
      if (start === null) start = currentTime;
      const timeElapsed = currentTime - start;
      const progress = Math.min(timeElapsed / duration, 1);
      const ease = easeInOutCubic(progress);
      
      window.scrollTo(0, startPosition + distance * ease);
      
      if (progress < 1) {
        requestAnimationFrame(animation);
      }
    };
    
    requestAnimationFrame(animation);
  }

  recalculate() {
    this.checkAnimations();
  }
}

// Initialize
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    new ScrollAnimations();
  });
} else {
  new ScrollAnimations();
}
