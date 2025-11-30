document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu-overlay');
    const body = document.body;
    const mobileLinks = document.querySelectorAll('.mobile-links a');

    // 1. Scroll Handling (Floating to Docked transition) - Optimized with RAF
    let scrollTicking = false;
    
    window.addEventListener('scroll', () => {
        if (!scrollTicking) {
            window.requestAnimationFrame(() => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    }, { passive: true });

    // 2. Mobile Menu Toggle
    hamburger.addEventListener('click', () => {
        const isActive = hamburger.classList.toggle('active');
        mobileMenu.classList.toggle('active');
        
        // Enhanced scroll lock for iOS Safari compatibility
        if (isActive) {
            // Store current scroll position
            const scrollY = window.scrollY;
            body.style.position = 'fixed';
            body.style.top = `-${scrollY}px`;
            body.style.width = '100%';
            body.style.overflow = 'hidden';
            body.dataset.scrollY = scrollY;
        } else {
            // Restore scroll position
            const scrollY = body.dataset.scrollY;
            body.style.position = '';
            body.style.top = '';
            body.style.width = '';
            body.style.overflow = '';
            window.scrollTo(0, parseInt(scrollY || '0'));
            delete body.dataset.scrollY;
        }
    });

    // 3. Close mobile menu when a link is clicked
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
            // Restore scroll position
            const scrollY = body.dataset.scrollY;
            body.style.position = '';
            body.style.top = '';
            body.style.width = '';
            body.style.overflow = '';
            if (scrollY) {
                window.scrollTo(0, parseInt(scrollY));
                delete body.dataset.scrollY;
            }
        });
    });

    // 4. Optimized instant smooth scrolling
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    // Pre-calculate navbar offset
    const navbarHeight = navbar ? navbar.offsetHeight : 80;
    
    navLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            
            // Skip if it's just "#" or empty
            if (!href || href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                
                // Calculate target position with offset
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                const startPosition = window.pageYOffset;
                const distance = targetPosition - startPosition;
                
                // Faster, more responsive scroll
                const duration = 600; // Reduced from 800ms
                let startTime = null;
                
                // Optimized easing function (easeOutCubic - faster feel)
                function easeOutCubic(t) {
                    return 1 - Math.pow(1 - t, 3);
                }
                
                function animation(currentTime) {
                    if (startTime === null) startTime = currentTime;
                    const timeElapsed = currentTime - startTime;
                    const progress = Math.min(timeElapsed / duration, 1);
                    
                    // Apply easing
                    const easedProgress = easeOutCubic(progress);
                    
                    window.scrollTo(0, startPosition + (distance * easedProgress));
                    
                    if (progress < 1) {
                        requestAnimationFrame(animation);
                    }
                }
                
                // Start animation immediately
                requestAnimationFrame(animation);
            }
        });
    });
});