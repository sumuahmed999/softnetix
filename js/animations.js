// ===================================
// Animation & Scroll Effects
// ===================================

/**
 * Initialize scroll animations using IntersectionObserver
 */
function initScrollAnimations() {
  // Check if IntersectionObserver is supported
  if (!('IntersectionObserver' in window)) {
    // Fallback: show all content immediately
    console.warn('IntersectionObserver not supported. Showing all content.');
    const animatedElements = document.querySelectorAll('.fade-in, .fade-in-left');
    animatedElements.forEach(el => {
      el.style.opacity = '1';
      el.style.transform = 'none';
    });
    return;
  }

  // Create IntersectionObserver
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.2 // Trigger when 20% of element is visible
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Add animation class when element enters viewport
        entry.target.classList.add('animate-in');
        
        // Optional: Stop observing after animation
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all sections except hero (hero animates on load)
  const sections = document.querySelectorAll('section:not(.hero)');
  sections.forEach(section => {
    observer.observe(section);
  });

  // Observe elements with fade-in classes
  const fadeElements = document.querySelectorAll('.fade-in, .fade-in-left');
  fadeElements.forEach(element => {
    observer.observe(element);
  });
}

/**
 * Add stagger effect to multiple elements in the same container
 */
function addStaggerEffect(container, selector) {
  const elements = container.querySelectorAll(selector);
  elements.forEach((element, index) => {
    element.classList.add(`stagger-${Math.min(index + 1, 5)}`);
  });
}

/**
 * Initialize scroll-triggered animations for about section
 */
function initAboutAnimations() {
  // Check if IntersectionObserver is supported
  if (!('IntersectionObserver' in window)) {
    // Fallback: show all content immediately
    const aboutElements = document.querySelectorAll('.about-content, .about-text, .about-icons, .about-icon-item');
    aboutElements.forEach(el => {
      el.classList.add('fade-in-view');
    });
    return;
  }

  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.2
  };

  const aboutObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in-view');
        aboutObserver.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe about section elements
  const aboutContent = document.querySelector('.about-content');
  const aboutText = document.querySelector('.about-text');
  const aboutIcons = document.querySelector('.about-icons');
  const aboutIconItems = document.querySelectorAll('.about-icon-item');

  if (aboutContent) aboutObserver.observe(aboutContent);
  if (aboutText) aboutObserver.observe(aboutText);
  if (aboutIcons) aboutObserver.observe(aboutIcons);
  aboutIconItems.forEach(item => aboutObserver.observe(item));
}

/**
 * Initialize all animations when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
  initScrollAnimations();
  initAboutAnimations();
  
  // Hero section animations are handled by CSS
  // Additional animation initialization will be added in subsequent tasks
});
