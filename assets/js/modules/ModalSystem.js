/**
 * سیستم مدیریت مودال‌های سالنامه - نسخه اصلاح شده
 */
class ModalSystem {
    constructor() {
        this.modals = new Map();
        this.activeModal = null;
        this.init();
    }

    init() {
        console.log('🚀 ModalSystem initialized - Fixed Version');
        this.registerExistingModals();
        this.setupEventListeners();
        this.setupGlobalMethods();
    }

    registerExistingModals() {
        const modalWrappers = document.querySelectorAll('.salnama-modal');
        
        console.log(`🔍 Found ${modalWrappers.length} modals`);
        
        modalWrappers.forEach(modal => {
            const modalType = modal.dataset.modalType;
            if (modalType) {
                this.registerModal(modalType, modal);
                console.log(`✅ Modal registered: ${modalType}`);
            }
        });
    }

    registerModal(modalId, modalElement) {
        this.modals.set(modalId, modalElement);
    }

    openModal(modalId) {
        console.log(`🔄 Opening: ${modalId}`);
        
        const modal = this.modals.get(modalId);
        if (modal) {
            this.closeActiveModal();
            modal.classList.remove('hidden');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            this.activeModal = modalId;
            
            // فوکوس روی اولین المان قابل فوکوس برای دسترسی‌پذیری
            const focusableElement = modal.querySelector('input, button, select, textarea');
            if (focusableElement) {
                focusableElement.focus();
            }
        } else {
            console.error(`❌ Modal not found: ${modalId}`);
        }
    }

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

    closeAllModals() {
        this.modals.forEach((modal) => {
            modal.classList.remove('active');
            modal.classList.add('hidden');
        });
        document.body.style.overflow = '';
        this.activeModal = null;
        console.log('🔴 All modals closed');
    }

    setupEventListeners() {
        console.log('🔧 Setting up event listeners...');

        // کلیک روی triggerهای مودال
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-trigger]');
            if (trigger) {
                console.log('🎯 Modal trigger clicked:', trigger);
                e.preventDefault();
                e.stopPropagation();
                const modalId = trigger.dataset.modalTrigger;
                this.openModal(modalId);
            }
        });

        // کلیک روی دکمه‌های بستن - اصلاح شده
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest('[data-modal-close]');
            if (closeBtn) {
                console.log('🔴 Close button clicked');
                e.preventDefault();
                e.stopPropagation();
                this.closeAllModals();
            }
        });

        // کلیک روی overlay - منطق اصلاح شده
        document.addEventListener('click', (e) => {
            // اگر مودال فعالی وجود دارد و روی overlay کلیک شده
            if (this.activeModal && e.target.classList.contains('modal-overlay')) {
                console.log('🔴 Overlay clicked - closing modal');
                this.closeAllModals();
            }
        });

        // جلوگیری از بسته شدن وقتی روی محتوای مودال کلیک می‌شود
        document.addEventListener('click', (e) => {
            const modalContent = e.target.closest('.modal-content');
            if (modalContent && this.activeModal) {
                console.log('📦 Modal content clicked - preventing close');
                e.stopPropagation();
            }
        });

        // کلید ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModal) {
                console.log('🔴 ESC key pressed');
                this.closeAllModals();
            }
        });

        // مدیریت ارسال فرم
        document.addEventListener('submit', (e) => {
            const form = e.target.closest('.contact-form');
            if (form) {
                console.log('📝 Form submitted');
                e.preventDefault();
                this.handleFormSubmit(form);
            }
        });

        console.log('✅ Event listeners setup complete');
    }

    handleFormSubmit(form) {
        console.log('🔄 Handling form submission');
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        console.log('📋 Form data:', data);
        
        // شبیه‌سازی ارسال فرم
        const submitBtn = form.querySelector('.submit-btn');
        const originalText = submitBtn.textContent;
        
        submitBtn.textContent = 'در حال ارسال...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            console.log('✅ Form submitted successfully');
            alert('درخواست شما با موفقیت ثبت شد! کارشناسان ما به زودی با شما تماس خواهند گرفت.');
            this.closeAllModals();
            form.reset();
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 2000);
    }

    setupGlobalMethods() {
        window.salnamaModals = this;
        window.openModal = (modalId) => this.openModal(modalId);
        window.closeModal = () => this.closeAllModals();
        
        console.log('✅ Global methods registered');
    }
}

// راه‌اندازی سیستم
document.addEventListener('DOMContentLoaded', () => {
    new ModalSystem();
});