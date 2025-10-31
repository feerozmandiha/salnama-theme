<?php
namespace Salnama_Theme\Core;

/**
 * Handles all core theme setup tasks such as adding theme support features
 * and registering navigation menus.
 */
class ThemeSetup {

    public function run() {
        add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
        add_action( 'init', [ $this, 'register_nav_menus' ] );
        add_filter( 'upload_mimes', [ $this, 'allow_svg_uploads' ] );
        add_action( 'admin_head', [ $this, 'fix_svg_display' ] );

        // غیرفعال کردن Layout Styles داینامیک برای Group Block
        add_filter( 'block_core_group_render_layout_support', '__return_false' );
    }

    /**
     * Adds support for various WordPress features and functionalities.
     */
    public function setup_theme() {
        // پشتیبانی از ویژگی‌های FSE
        add_theme_support('wp-block-styles');
        add_theme_support('editor-styles');
        add_theme_support('appearance-tools');
        add_theme_support('custom-spacing');
        add_theme_support('custom-line-height');
        
        // پشتیبانی از محتوای تمام عرض
        add_theme_support('align-wide');
        add_theme_support('block-templates');

        // مدیریت عنوان داینامیک
        add_theme_support('title-tag');
        
        // تصویر شاخص
        add_theme_support('post-thumbnails');
        add_theme_support('responsive-embeds');

        // HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style'
        ]);
        
        // ترجمه
        load_theme_textdomain('salnama-theme', get_template_directory() . '/languages');
        
        error_log('✅ Theme setup completed');
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
        
        error_log('✅ Navigation menus registered');
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