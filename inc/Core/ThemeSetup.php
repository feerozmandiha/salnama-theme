<?php
namespace Salnama_Theme\Core;

/**
 * Handles all core theme setup tasks such as adding theme support features
 * and registering navigation menus.
 */
class ThemeSetup {

    public function run() {
        add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
        add_action( 'init', [ $this, 'register_nav_menus' ] );
        add_filter( 'upload_mimes', [ $this, 'allow_svg_uploads' ] );
        add_action( 'admin_head', [ $this, 'fix_svg_display' ] );

        // غیرفعال کردن Layout Styles داینامیک برای Group Block (جلوگیری از کلاس‌های .wp-container-...)
        add_filter( 'block_core_group_render_layout_support', '__return_false' );
    }

    /**
     * Adds support for various WordPress features and functionalities.
     */
    public function theme_support() {
        // فعال‌سازی عنوان داینامیک در تگ <title>
        add_theme_support( 'title-tag' );
        add_theme_support( 'editor-styles' );
        add_theme_support( 'editor-font-sizes' );

        // فعال‌سازی پشتیبانی از پست‌تامبنیل
        add_theme_support( 'post-thumbnails' );

        // تعیین محتوای اصلی تمپلیت
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ] );

        // پشتیبانی از بلوک‌های عریض و تمام عرض
        add_theme_support( 'align-wide' );

        // غیرفعال کردن استایل‌های پیش‌فرض بلوک‌ها (برای استفاده کامل از Tailwind)
        // add_theme_support( 'wp-block-styles' );

        // مدیریت ترجمه
        load_theme_textdomain( 'salnama-theme', SALNAMA_THEME_PATH . '/languages' );

        // تنظیمات theme.json
        add_theme_support( 'custom-spacing' );
        add_theme_support( 'custom-border' );
        add_theme_support( 'custom-units', [ 'rem', 'em', 'px', '%', 'vh', 'vw' ] );
    }
    
    /**
     * Registers navigation menus for the theme.
     */
    public function register_nav_menus() {
        register_nav_menus( [
            'header-menu' => esc_html__( 'منوی اصلی هدر', 'salnama-theme' ),
            'footer-menu' => esc_html__( 'منوی فوتر (ناوبری)', 'salnama-theme' ),
            'mobile-menu' => esc_html__( 'منوی تمام صفحه (Off-Canvas)', 'salnama-theme' ),
        ] );
    }

    // اجازه بارگذاری فایل‌های SVG
    public function allow_svg_uploads( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    // جلوگیری از خطای نمایش در پیشخوان
    public function fix_svg_display() {
        echo '<style>
            img[src$=".svg"] {
                width: 100% !important;
                height: auto !important;
            }
        </style>';
    }


}