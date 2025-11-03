<?php
namespace Salnama_Theme\Core;

class LoginLogo {
    public function run() {
        add_action('login_enqueue_scripts', [$this, 'add_custom_logo_style']);
        add_filter('login_headerurl', [$this, 'change_logo_url']);
        add_filter('login_headertext', [$this, 'change_logo_title']);
    }

    /**
     * Injects custom CSS into the login page to replace the logo.
     */
    public function add_custom_logo_style() {
        $logo_url = $this->get_site_logo_url();

        if ( ! $logo_url ) {
            return; // اگر لوگو تنظیم نشده باشد، هیچ کاری نکن
        }

        echo '<style type="text/css">
            .login h1 a {
                background-image: url("' . esc_url($logo_url) . '");
                background-size: contain;
                background-repeat: no-repeat;
                width: 100%;
                height: 100px;
                display: block;
            }
        </style>';
    }

    /**
     * Returns the site logo URL from custom_logo setting.
     */
    private function get_site_logo_url(): ?string {
        $custom_logo_id = get_theme_mod('custom_logo');
        if ( ! $custom_logo_id ) {
            return null;
        }

        $logo_data = wp_get_attachment_image_src($custom_logo_id, 'full');
        return $logo_data[0] ?? null;
    }

    public function change_logo_url() {
        return home_url();
    }

    public function change_logo_title() {
        return get_bloginfo('name');
    }
}
