/**
 * سیستم مدرن مدیریت مودال‌های سالنامه
 * وردپرس 6.8 + گوتنبرگ 21.9.0
 */

class ModalSystem {
    constructor() {
        this.modals = new Map();
        this.activeModal = null;
        this.init();
    }

    init() {
        console.log('🚀 ModalSystem initialized');
        this.registerExistingModals();
        this.setupEventListeners();
        this.setupGlobalMethods();
    }

    /**
     * ثبت مودال‌های موجود در DOM
     */
    registerExistingModals() {
        const modalWrappers = document.querySelectorAll('.salnama-modal');
        
        console.log(`🔍 Found ${modalWrappers.length} modals`);
        
        modalWrappers.forEach(modal => {
            const modalType = modal.dataset.modalType;
            if (modalType) {
                this.registerModal(modalType, modal);
            }
        });
    }

    /**
     * ثبت یک مودال جدید
     */
    registerModal(modalId, modalElement) {
        this.modals.set(modalId, modalElement);
        console.log(`✅ Modal registered: ${modalId}`);
    }

    /**
     * باز کردن مودال
     */
    openModal(modalId) {
        console.log(`🔄 Opening modal: ${modalId}`);
        
        const modal = this.modals.get(modalId);
        if (modal) {
            // بستن مودال فعال قبلی
            this.closeActiveModal();
            
            // نمایش مودال جدید
            modal.classList.remove('hidden');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            this.activeModal = modalId;
            console.log(`✅ Modal opened: ${modalId}`);
        } else {
            console.error(`❌ Modal not found: ${modalId}`);
        }
    }

    /**
     * بستن مودال فعال
     */
    closeActiveModal() {
        if (this.activeModal) {
            const modal = this.modals.get(this.activeModal);
            if (modal) {
                modal.classList.remove('active');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                console.log(`✅ Modal closed: ${this.activeModal}`);
            }
            this.activeModal = null;
        }
    }

    /**
     * بستن تمام مودال‌ها
     */
    closeAllModals() {
        this.modals.forEach((modal, modalId) => {
            modal.classList.remove('active');
            modal.classList.add('hidden');
        });
        document.body.style.overflow = '';
        this.activeModal = null;
        console.log('🔴 All modals closed');
    }

    /**
     * تنظیم event listeners
     */
    setupEventListeners() {
        // کلیک روی triggerهای مودال
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-trigger]');
            if (trigger) {
                e.preventDefault();
                const modalId = trigger.dataset.modalTrigger;
                this.openModal(modalId);
            }
        });

        // کلیک روی دکمه‌های بستن
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest('[data-modal-close]');
            if (closeBtn) {
                e.preventDefault();
                this.closeAllModals();
            }
        });

        // کلیک روی overlay
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.closeAllModals();
            }
        });

        // کلید ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        console.log('✅ Event listeners setup complete');
    }

    /**
     * تنظیم متدهای global
     */
    setupGlobalMethods() {
        window.salnamaModals = this;
        window.openModal = (modalId) => this.openModal(modalId);
        window.closeModal = () => this.closeAllModals();
        
        console.log('✅ Global methods registered');
    }

    /**
     * دریافت وضعیت سیستم
     */
    getStatus() {
        return {
            registeredModals: Array.from(this.modals.keys()),
            activeModal: this.activeModal,
            totalModals: this.modals.size
        };
    }
}

// راه‌اندازی سیستم
document.addEventListener('DOMContentLoaded', () => {
    new ModalSystem();
});

console.log('📜 ModalSystem.js loaded');