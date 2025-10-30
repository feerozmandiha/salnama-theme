<?php
namespace Salnama_Theme\Core;

/**
 * مدیریت بارگذاری فایل‌های CSS و جاوااسکریپت
 * این کلاس تمام منابع استاتیک قالب را ثبت می‌کند.
 */
class AssetsLoader {

    /**
     * ثبت هک‌ها و فیلترهای مربوط به بارگذاری منابع
     */
    public function run() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'script_loader_tag', [ $this, 'add_module_type_to_app_js' ], 10, 3 );
    }

    /**
     * بارگذاری فایل‌های استایل
     */
    public function enqueue_styles() {
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