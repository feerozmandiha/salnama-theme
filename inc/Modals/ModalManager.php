<?php
namespace Salnama_Theme\Modals;

/**
 * سیستم مدیریت مرکزی مودال‌ها
 */
class ModalManager {
    
    private static $instance = null;
    private $registered_modals = [];

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run() {  // ✅ تغییر از init به run برای سازگاری
        add_action('wp_footer', [$this, 'render_modals']);
        add_action('wp_ajax_get_modal_content', [$this, 'ajax_get_modal_content']);
        add_action('wp_ajax_nopriv_get_modal_content', [$this, 'ajax_get_modal_content']);
        
        $this->auto_register_modals();
        
        error_log('✅ ModalManager initialized');
    }

    /**
     * ثبت مودال جدید در سیستم
     */
    public function register_modal($modal_slug, $config = []) {
        $default_config = [
            'type' => $modal_slug,
            'title' => '',
            'content' => '',
            'template' => '',
            'ajax' => false,
            'classes' => '',
            'size' => 'medium',
            'trigger_selectors' => [],
            'conditions' => []
        ];

        $this->registered_modals[$modal_slug] = wp_parse_args($config, $default_config);
        
        error_log("✅ مودال ثبت شد: {$modal_slug}");
    }

    /**
     * ثبت اتوماتیک مودال‌ها از پترن‌ها
     */
    private function auto_register_modals() {
        // ابتدا مودال‌های ثابت را ثبت کن
        $this->register_core_modals();
        
        // سپس مودال‌ها از پترن‌ها را اسکن کن
        $modal_patterns = $this->scan_modal_patterns();
        
        foreach ($modal_patterns as $pattern) {
            $this->register_modal_from_pattern($pattern);
        }
        
        error_log('✅ Auto-registration completed');
    }

    /**
     * ثبت مودال‌های هسته سیستم
     */
    private function register_core_modals() {
        // مودال تماس هدر
        $this->register_modal('header-contact', [
            'title' => 'دریافت مشاوره تخصصی',
            'template' => 'header-contact-modal',
            'ajax' => false,
            'size' => 'medium',
            'conditions' => [],
            'trigger_selectors' => ['[data-modal-trigger="header-contact"]']
        ]);

        // مودال مشاوره
        $this->register_modal('consultation', [
            'title' => 'مشاوره رایگان',
            'template' => 'consultation-modal', 
            'ajax' => false,
            'size' => 'large',
            'conditions' => []
        ]);

        error_log("✅ مودال‌های هسته سیستم ثبت شدند");
    }

    /**
     * اسکن پترن‌های مودال از پوشه patterns/modals/
     */
    private function scan_modal_patterns() {
        $patterns_dir = get_template_directory() . '/patterns/modals/';
        $patterns = [];
        
        if (is_dir($patterns_dir)) {
            $files = scandir($patterns_dir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
                    $pattern_name = str_replace('.html', '', $file);
                    $patterns[] = $pattern_name;
                }
            }
        }
        
        error_log("پترن‌های مودال یافت شده: " . implode(', ', $patterns));
        return $patterns;
    }

    /**
     * ثبت مودال از پترن
     */
    private function register_modal_from_pattern($pattern_name) {
        $pattern_file = get_template_directory() . "/patterns/modals/{$pattern_name}.html";
        
        if (file_exists($pattern_file)) {
            $content = file_get_contents($pattern_file);
            $modal_data = $this->parse_pattern_metadata($content);
            
            $this->register_modal($modal_data['slug'], [
                'title' => $modal_data['title'],
                'template' => $pattern_name,
                'classes' => $modal_data['classes'],
                'trigger_selectors' => ["[data-modal-trigger='{$modal_data['slug']}']"]
            ]);

            error_log("مودال از پترن ثبت شد: {$pattern_name} -> {$modal_data['slug']}");
        } else {
            error_log("خطا: فایل پترن یافت نشد: {$pattern_file}");
        }
    }

    /**
     * پارس metadata از پترن
     */
    private function parse_pattern_metadata($content) {
        // استخراج metadata از کامنت‌های پترن
        preg_match('/data-modal-type="([^"]*)"/', $content, $type_matches);
        preg_match('/class="[^"]*modal-([^"\s]*)/', $content, $class_matches);
        
        return [
            'slug' => $type_matches[1] ?? 'default',
            'title' => $this->extract_title($content),
            'classes' => $class_matches[1] ?? ''
        ];
    }

    private function extract_title($content) {
        preg_match('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/', $content, $matches);
        return $matches[1] ?? __('مودال', 'salnama-theme');
    }

    /**
     * رندر مودال‌ها در فوتر
     */
    public function render_modals() {
        echo '<!-- Salnama Modal System Start -->';
        
        foreach ($this->registered_modals as $modal_slug => $config) {
            if (!$config['ajax']) {
                echo $this->get_modal_html($modal_slug);
            }
        }
        
        // رندر container برای مودال‌های AJAX
        echo '<div id="salnama-ajax-modals-container" style="display: none;"></div>';
        
        echo '<!-- Salnama Modal System End -->';
        
        error_log("مودال‌ها رندر شدند: " . implode(', ', array_keys($this->registered_modals)));
    }

    /**
     * تولید HTML مودال
     */
    public function get_modal_html($modal_slug, $args = []) {
        if (!isset($this->registered_modals[$modal_slug])) {
            error_log("خطا: مودال یافت نشد: {$modal_slug}");
            return '';
        }

        $modal_config = $this->registered_modals[$modal_slug];
        
        // اگر تمپلیت مشخص شده، از فایل پترن استفاده کن
        if (!empty($modal_config['template'])) {
            $template_file = get_template_directory() . "/patterns/modals/{$modal_config['template']}.html";
            
            if (file_exists($template_file)) {
                $content = file_get_contents($template_file);
                $processed_content = $this->process_modal_template($content, $args);
                error_log("تمپلیت مودال لود شد: {$modal_config['template']}");
                return $processed_content;
            } else {
                error_log("خطا: فایل تمپلیت یافت نشد: {$template_file}");
            }
        }

        // اگر تمپلیت وجود ندارد، HTML داینامیک تولید کن
        return $this->generate_modal_html($modal_config, $args);
    }

    /**
     * پردازش تمپلیت مودال
     */
    private function process_modal_template($content, $args) {
        // جایگزینی متغیرها در تمپلیت
        foreach ($args as $key => $value) {
            $content = str_replace("{{{$key}}}", $value, $content);
        }
        
        return $content;
    }

    /**
     * تولید HTML مودال به صورت داینامیک
     */
    private function generate_modal_html($config, $args) {
        ob_start();
        ?>
        <div class="salnama-modal modal-wrapper modal-<?php echo esc_attr($config['type']); ?> hidden" 
             data-modal-type="<?php echo esc_attr($config['type']); ?>">
            
            <div class="modal-overlay" data-modal-close="true"></div>
            
            <div class="modal-content modal-size-<?php echo esc_attr($config['size']); ?>">
                <button class="modal-close-btn" data-modal-close="true" aria-label="بستن">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </button>
                
                <div class="modal-header">
                    <h3><?php echo esc_html($config['title']); ?></h3>
                </div>
                
                <div class="modal-body">
                    <?php echo $config['content']; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * هندلر AJAX برای دریافت محتوای مودال
     */
    public function ajax_get_modal_content() {
        // بررسی nonce برای امنیت
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'salnama_modal_nonce')) {
            wp_send_json_error('Nonce verification failed');
        }

        $modal_slug = sanitize_text_field($_POST['modal_slug'] ?? '');
        $args = isset($_POST['modal_args']) ? array_map('sanitize_text_field', $_POST['modal_args']) : [];

        if (empty($modal_slug)) {
            wp_send_json_error('Modal slug required');
        }

        $html = $this->get_modal_html($modal_slug, $args);
        wp_send_json_success(['html' => $html]);
    }

    /**
     * بررسی شرایط نمایش مودال
     */
    public function should_display_modal($modal_slug, $context = []) {
        $modal_config = $this->registered_modals[$modal_slug] ?? [];
        
        if (empty($modal_config['conditions'])) {
            return true;
        }

        // پیاده سازی منطق شرایط نمایش
        foreach ($modal_config['conditions'] as $condition) {
            if (!$this->check_condition($condition, $context)) {
                return false;
            }
        }

        return true;
    }

    private function check_condition($condition, $context) {
        // پیاده سازی منطق شرط‌ها
        // مثلاً: صفحه خاص، کاربر خاص، زمان خاص و...
        
        switch ($condition['type'] ?? '') {
            case 'page':
                return is_page($condition['value'] ?? '');
                
            case 'post_type':
                return is_singular($condition['value'] ?? '');
                
            case 'user_role':
                if (is_user_logged_in()) {
                    $user = wp_get_current_user();
                    return in_array($condition['value'] ?? '', $user->roles);
                }
                return false;
                
            case 'logged_in':
                return is_user_logged_in();
                
            default:
                return true;
        }
    }

    /**
     * دریافت لیست مودال‌های ثبت شده (برای دیباگ)
     */
    public function get_registered_modals() {
        return $this->registered_modals;
    }

    /**
     * بررسی وجود مودال
     */
    public function modal_exists($modal_slug) {
        return isset($this->registered_modals[$modal_slug]);
    }
}