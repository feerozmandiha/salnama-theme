import { isDesktop } from '../core/utils.js';

/**
 *  کلاس مدیریت هدر عمودی — با انیمیشن‌های ملایم و طبیعی (سبک bras.fr)
 */
export class Header {
    constructor() {
        this.header = document.querySelector('.minimal-vertical-header');
        if (!this.header) return;

        this.toggleArea = this.header.querySelector('.menu-toggle-area');
        this.menuIcon = this.header.querySelector('.menu-icon');
        this.overlay = document.querySelector('.full-screen-menu-overlay');
        this.logoContainer = this.header.querySelector('.logo-container');
        this.ctaButton = this.header.querySelector('.cta-button-wrapper');
        this.arrowPath = this.header.querySelector('.arrow-path');

        if (!this.toggleArea || !this.overlay) return;

        this.isMenuOpen = false;
        this.lastScrollTop = 0;

        this.init();
    }

    get fullHeight() {
        return window.innerHeight;
    }

    remToPx(rem) {
            return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
        }

    init() {
        this.initHoverEvents();
        this.initClickEvents();
        this.setArrowLoop('initial');

        window.addEventListener('scroll', () => this.handleScroll());
        window.addEventListener('resize', () => this.handleResize());
    }

    setArrowLoop(state) {
        if (!this.arrowPath || this.isMenuOpen) return;
        this.arrowPath.classList.remove('animate-loop-initial', 'animate-loop-hovered');
        this.arrowPath.classList.add(state === 'initial' ? 'animate-loop-initial' : 'animate-loop-hovered');
    }

initHoverEvents() {
    if (!this.toggleArea || !isDesktop()) return;

    let leaveTimeout = null;
    let isOverHeaderOrOverlay = false;

    // کنترل ورود موس به overlay
    if (this.overlay) {
        this.overlay.addEventListener('mouseenter', () => {
            isOverHeaderOrOverlay = true;
            if (leaveTimeout) {
                clearTimeout(leaveTimeout);
                leaveTimeout = null;
            }
        });

        this.overlay.addEventListener('mouseleave', () => {
            isOverHeaderOrOverlay = false;
            if (this.isMenuOpen) {
                leaveTimeout = setTimeout(() => {
                    this.toggleMenu();
                    leaveTimeout = null;
                }, 2000);
            }
        });
    }

    this.toggleArea.addEventListener('mouseenter', () => {
        if (this.isMenuOpen) return;

        isOverHeaderOrOverlay = true;
        if (leaveTimeout) {
            clearTimeout(leaveTimeout);
            leaveTimeout = null;
        }

        gsap.to(this.header, {
            height: this.fullHeight - this.remToPx(2),
            duration: 0.7,
            ease: 'expo.out',
            onComplete: () => {
                if (this.logoContainer) {
                    this.logoContainer.style.opacity = '1';
                    this.logoContainer.style.visibility = 'visible';
                    this.logoContainer.style.transform = 'translateX(0)';
                }
            }
        });

        const targets = [];
        if (this.logoContainer) targets.push(this.logoContainer);
        if (this.ctaButton) targets.push(this.ctaButton);
        if (targets.length > 0) {
            gsap.to(targets, {
                autoAlpha: 1,
                duration: 0.5,
                delay: 0.25,
                ease: 'circ.out'
            });
        }

        if (this.menuIcon) {
            this.menuIcon.classList.add('is-rotated-90');
            this.menuIcon.classList.remove('is-rotated-180');
        }

        this.setArrowLoop('hovered');
    });

    this.header.addEventListener('mouseenter', () => {
        isOverHeaderOrOverlay = true;
        if (leaveTimeout) {
            clearTimeout(leaveTimeout);
            leaveTimeout = null;
        }
    });

    this.header.addEventListener('mouseleave', () => {
        if (!isDesktop()) return;

        isOverHeaderOrOverlay = false;

        // اگر منو باز است، فقط تایمر بسته شدن منو فعال شود
        if (this.isMenuOpen) {
            leaveTimeout = setTimeout(() => {
                if (!isOverHeaderOrOverlay) {
                    this.toggleMenu();
                }
                leaveTimeout = null;
            }, 2000);
        } else {
            // حالت hover معمولی
            leaveTimeout = setTimeout(() => {
                if (!isOverHeaderOrOverlay) {
                    if (this.logoContainer) {
                        this.logoContainer.style.opacity = '0';
                        this.logoContainer.style.visibility = 'hidden';
                        this.logoContainer.style.transform = 'translateX(20px)';
                    }

                    gsap.to(this.header, {
                        height: '22vh',
                        duration: 0.6,
                        ease: 'expo.in'
                    });

                    const targets = [];
                    if (this.logoContainer) targets.push(this.logoContainer);
                    if (this.ctaButton) targets.push(this.ctaButton);
                    if (targets.length > 0) {
                        gsap.to(targets, {
                            autoAlpha: 0,
                            duration: 0.3,
                            ease: 'circ.in'
                        });
                    }

                    if (this.menuIcon) {
                        this.menuIcon.classList.remove('is-rotated-90', 'is-rotated-180');
                    }

                    this.setArrowLoop('initial');
                }
                leaveTimeout = null;
            }, 2000);
        }
    });
}


    initClickEvents() {
        if (!this.toggleArea || !this.overlay) return;

        this.toggleArea.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleMenu();
        });

        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.toggleMenu();
            }
        });
    }

    toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;

        if (this.isMenuOpen) {
            if (this.arrowPath) {
                this.arrowPath.classList.remove('animate-loop-initial', 'animate-loop-hovered');
            }

            if (this.header) {
                this.header.classList.add('is-expanded-menu');
            }

            if (isDesktop()) {
                gsap.to(this.header, {
                    height: this.fullHeight - this.remToPx(2),
                    duration: 0.5,
                    ease: 'expo.out'
                });
            }

            gsap.to(this.overlay, {
                opacity: 1,
                pointerEvents: 'all',
                width: isDesktop() ? `calc(100%)` : '100%',
                duration: 0.6,
                ease: 'expo.out'
            });

            if (this.menuIcon) {
                this.menuIcon.classList.remove('is-rotated-90');
                this.menuIcon.classList.add('is-rotated-180');
            }

        } else {
            if (isDesktop()) {
                gsap.to(this.header, {
                    height: '22vh',
                    duration: 0.5,
                    ease: 'expo.in'
                });
            }

            if (this.header) {
                this.header.classList.remove('is-expanded-menu');
            }

            gsap.to(this.overlay, {
                opacity: 0,
                pointerEvents: 'none',
                width: '100%',
                duration: 0.4,
                ease: 'expo.in'
            });

            if (this.menuIcon) {
                this.menuIcon.classList.remove('is-rotated-90', 'is-rotated-180');
            }

            this.setArrowLoop('initial');
        }
    }



    handleScroll() {
        if (isDesktop() || this.isMenuOpen) return;
        const st = window.scrollY;
        const headerHeight = this.header.offsetHeight;

        if (st > this.lastScrollTop && st > headerHeight) {
            gsap.to(this.header, {
                y: -headerHeight - this.remToPx(2),
                duration: 0.4,
                ease: 'expo.in'
            });
        } else {
            gsap.to(this.header, {
                y: 0,
                duration: 0.4,
                ease: 'expo.out'
            });
        }
        this.lastScrollTop = st;
    }

    handleResize() {
        if (isDesktop()) {
            if (!this.isMenuOpen) {
                gsap.set(this.header, { height: '22vh', y: 0 });
                this.setArrowLoop('initial');
            }
        } else {
            gsap.set(this.header, { height: 'auto', y: 0 });
            if (!this.isMenuOpen) {
                this.setArrowLoop('initial');
            }
        }
    }
}