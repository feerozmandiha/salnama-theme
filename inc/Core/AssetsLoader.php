<?php
namespace Salnama_Theme\Core;

class AssetsLoader {

    public function run() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
        add_filter( 'script_loader_tag', [ $this, 'add_module_type_to_app_js' ], 10, 3 );
        
        error_log('✅ AssetsLoader initialized');
    }

    public function enqueue_styles() {
        // استایل‌های اصلی
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

        // استایل‌های ضروری برای مودال‌ها
        wp_enqueue_style(
            'salnama-modal-essential',
            SALNAMA_ASSETS_URI . '/css/modals/modal-essential.css',
            [],
            SALNAMA_THEME_VERSION
        );
        
        error_log('✅ Frontend styles enqueued');
    }

    public function enqueue_editor_assets() {
        // استایل‌های ویرایشگر
        wp_enqueue_style(
            'salnama-editor-styles',
            SALNAMA_ASSETS_URI . '/css/editor.css',
            ['wp-edit-blocks'],
            SALNAMA_THEME_VERSION
        );
        
        error_log('✅ Editor styles enqueued');
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

        // اسکریپت اصلی برنامه
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

    /**
     * افزودن type="module" به اسکریپت‌های ماژولار
     */
    public function add_module_type_to_app_js( $tag, $handle, $src ) {
        $module_handles = [
            'salnama-theme-app-js',
            'salnama-modal-system'
        ];
        
        if ( in_array( $handle, $module_handles ) ) {
            return '<script type="module" src="' . esc_url( $src ) . '"></script>';
        }
        return $tag;
    }
}