<?php
namespace Salnama_Theme\Core;

/**
 * مدیریت بارگذاری فایل‌های CSS و جاوااسکریپت
 * این کلاس تمام منابع استاتیک قالب را ثبت می‌کند.
 */
class AssetsLoader {

    public function run() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'script_loader_tag', [ $this, 'add_module_type_to_app_js' ], 10, 3 );
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_assets']);
        add_action('init', [$this, 'register_assets']);
        
        error_log('✅ AssetsLoader initialized');
    }

    /**
     * بارگذاری فایل‌های استایل
     */
    public function enqueue_styles() {
        // استایل اصلی تم
        wp_enqueue_style( 
            'salnama-theme-tailwind', 
            SALNAMA_ASSETS_URI . '/css/dist/tailwind.css', 
            [], 
            SALNAMA_THEME_VERSION 
        );

        wp_enqueue_style( 
            'salnama-theme-global-css', 
            SALNAMA_ASSETS_URI . '/css/global.css', 
            ['salnama-theme-tailwind'], 
            SALNAMA_THEME_VERSION 
        );

        // استایل‌های سیستم مودال
        wp_enqueue_style(
            'salnama-modal-system',
            SALNAMA_ASSETS_URI . '/css/modals/modal-system.css',
            [],
            SALNAMA_THEME_VERSION
        );

        wp_enqueue_style(
            'salnama-header-modal',
            SALNAMA_ASSETS_URI . '/css/modals/header-cta-modal.css',
            ['salnama-modal-system'],
            SALNAMA_THEME_VERSION
        );
        
        error_log('✅ Styles enqueued');
    }

    /**
     * بارگذاری فایل‌های جاوااسکریپت
     */
    public function enqueue_scripts() {
        // GSAP Core
        wp_enqueue_script(
            'gsap-core',
            'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js',
            [],
            '3.13.0',
            true
        );
        
        // ScrollTrigger
        wp_enqueue_script(
            'gsap-scrolltrigger',
            'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js',
            ['gsap-core'], 
            '3.13.0',
            true
        );

        // اسکریپت اصلی برنامه (به صورت ماژول)
        wp_enqueue_script(
            'salnama-theme-app-js',
            SALNAMA_ASSETS_URI . '/js/core/App.js', 
            ['gsap-scrolltrigger'], 
            SALNAMA_THEME_VERSION, 
            true
        );

        // اسکریپت سیستم مودال
        wp_enqueue_script(
            'salnama-modal-system',
            SALNAMA_ASSETS_URI . '/js/modules/ModalSystem.js',
            [],
            SALNAMA_THEME_VERSION,
            [
                'strategy' => 'defer',
                'in_footer' => true
            ]
        );
        
        error_log('✅ Scripts enqueued');
    }

    public function register_assets() {
        // ثبت استایل‌ها و اسکریپت‌ها برای استفاده مجدد
        wp_register_style(
            'salnama-modern-modals',
            SALNAMA_ASSETS_URI . '/css/modals/modal-system.css',
            [],
            SALNAMA_THEME_VERSION
        );
        
        wp_register_script(
            'salnama-modal-system',
            SALNAMA_ASSETS_URI . '/js/modules/ModalSystem.js',
            [],
            SALNAMA_THEME_VERSION,
            ['strategy' => 'defer', 'in_footer' => true]
        );
        
        error_log('✅ Assets registered');
    }

    public function enqueue_block_assets() {
        // استایل‌های ادیتور اختصاصی
        wp_enqueue_style(
            'salnama-editor-styles',
            SALNAMA_ASSETS_URI . '/css/editor.css',
            ['wp-edit-blocks'],
            SALNAMA_THEME_VERSION
        );
        
        error_log('✅ Block editor assets enqueued');
    }

    /**
     * افزودن type="module" به اسکریپت اصلی
     */
    public function add_module_type_to_app_js( $tag, $handle, $src ) {
        if ( 'salnama-theme-app-js' === $handle ) {
            return '<script type="module" src="' . esc_url( $src ) . '"></script>';
        }
        return $tag;
    }
}