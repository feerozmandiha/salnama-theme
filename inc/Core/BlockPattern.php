<?php
namespace Salnama_Theme\Core;

/**
 * مدیریت ثبت پترن‌های بلوک برای سالنامه
 * تمام پترن‌های CTA و مودال‌ها اینجا ثبت می‌شوند
 */
class BlockPattern {

    public function run() {
        add_action( 'init', [ $this, 'register_pattern_categories' ] );
        add_action( 'init', [ $this, 'register_all_patterns' ] );
        add_action( 'init', [ $this, 'register_block_styles' ] );
        
        error_log('✅ BlockPattern initialized');
    }

    /**
     * ثبت دسته‌بندی‌های پترن
     */
    public function register_pattern_categories() {
        register_block_pattern_category(
            'salnama-header',
            [ 'label' => esc_html__( 'هدرهای سالنما', 'salnama-theme' ) ]
        );
        
        register_block_pattern_category(
            'salnama-components',
            [ 'label' => esc_html__( 'اجزای تعاملی سالنما', 'salnama-theme' ) ]
        );

        // دسته‌بندی‌های مخصوص مودال و CTA
        register_block_pattern_category('salmama-modals', [
            'label' => __('مودال‌های سالنامه', 'salnama-theme')
        ]);
        
        register_block_pattern_category('salmama-cta', [
            'label' => __('فراخوان‌های سالنامه', 'salnama-theme')
        ]);
        
        error_log('✅ Pattern categories registered');
    }

    /**
     * ثبت تمام پترن‌ها
     */
    public function register_all_patterns() {
        $this->register_cta_patterns();
        $this->register_modal_patterns();
        $this->register_component_patterns();
        
        error_log('✅ All patterns registered');
    }

    /**
     * ثبت پترن‌های CTA
     */
    private function register_cta_patterns() {
        // پترن CTA هدر
        register_block_pattern(
            'salmama-cta/header-contact',
            [
                'title'       => __('CTA هدر - تماس و مشاوره', 'salnama-theme'),
                'categories'  => ['salmama-cta', 'salnama-header'],
                'description' => __('دکمه CTA مدرن برای هدر با مودال تماس', 'salnama-theme'),
                'content'     => $this->load_pattern_content('cta/header-cta-contact'),
            ]
        );

        // پترن CTA خدمات
        register_block_pattern(
            'salmama-cta/services-cta',
            [
                'title'       => __('CTA خدمات تخصصی', 'salnama-theme'),
                'categories'  => ['salmama-cta', 'salnama-components'],
                'description' => __('دکمه CTA برای خدمات تخصصی', 'salnama-theme'),
                'content'     => $this->load_pattern_content('cta/services-cta'),
            ]
        );
        
        error_log('✅ CTA patterns registered');
    }

    /**
     * ثبت پترن‌های مودال
     */
    private function register_modal_patterns() {
        // پترن مودال تماس هدر
        register_block_pattern(
            'salmama-modals/header-contact',
            [
                'title'       => __('مودال تماس هدر', 'salnama-theme'),
                'categories'  => ['salmama-modals'],
                'description' => __('مودال مدرن تماس و مشاوره برای هدر', 'salnama-theme'),
                'content'     => $this->load_pattern_content('modals/header-contact-modal'),
            ]
        );

        // پترن مودال مشاوره
        register_block_pattern(
            'salmama-modals/consultation',
            [
                'title'       => __('مودال مشاوره رایگان', 'salnama-theme'),
                'categories'  => ['salmama-modals'],
                'description' => __('مودال درخواست مشاوره رایگان', 'salnama-theme'),
                'content'     => $this->load_pattern_content('modals/consultation-modal'),
            ]
        );
        
        error_log('✅ Modal patterns registered');
    }

    /**
     * ثبت پترن‌های کامپوننت
     */
    private function register_component_patterns() {
        // پترن‌های کامپوننت‌های دیگر می‌توانند اینجا اضافه شوند
        // register_block_pattern(...);
    }

    /**
     * بارگذاری محتوای پترن از فایل
     */
    private function load_pattern_content($pattern_path) {
        $pattern_file = SALNAMA_THEME_PATH . '/patterns/' . $pattern_path . '.html';
        
        if (file_exists($pattern_file)) {
            $content = file_get_contents($pattern_file);
            return $content;
        }
        
        error_log("❌ فایل پترن یافت نشد: {$pattern_file}");
        return '<!-- پترن یافت نشد: ' . $pattern_path . ' -->';
    }

    /**
     * ثبت استایل‌های بلوک
     */
    public function register_block_styles() {
        // استایل‌های سفارشی برای دکمه‌های مودال
        register_block_style('core/button', [
            'name' => 'modal-trigger',
            'label' => __('تریگر مودال', 'salnama-theme')
        ]);
        
        register_block_style('core/button', [
            'name' => 'modal-close',
            'label' => __('بستن مودال', 'salnama-theme')
        ]);

        // استایل‌های سفارشی برای گروه‌ها
        register_block_style('core/group', [
            'name' => 'modal-overlay',
            'label' => __('پس‌زمینه مودال', 'salnama-theme')
        ]);

        register_block_style('core/group', [
            'name' => 'modal-content',
            'label' => __('محتوای مودال', 'salnama-theme')
        ]);
        
        error_log('✅ Block styles registered');
    }
}