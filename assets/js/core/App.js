//assets/js/core/App.js
import { Header } from '../modules/Header.js';

/**
 * Main application initializer for the Salnama Theme.
 * Initializes all modular JavaScript components.
 */
document.addEventListener('DOMContentLoaded', () => {
    // اطمینان از وجود GSAP قبل از ادامه کار
    if (typeof gsap === 'undefined') {
        console.warn("GSAP library not found. Skipping advanced animations.");
        return;
    }
    
    // مقداردهی اولیه ماژول هدر
    new Header();
    
    // ... سایر ماژول‌ها (مانند ScrollAnimations و ContactButton) در اینجا اضافه می‌شوند
});
