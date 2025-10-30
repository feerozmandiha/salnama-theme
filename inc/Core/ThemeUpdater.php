<?php
namespace Salnama_Theme\Core;

/**
 * Theme Auto-Updater
 * Handles automatic updates from GitHub releases
 */
class ThemeUpdater {

    /**
     * GitHub repository info
     */
    private $github_username = 'feerozmandiha';
    private $github_repo = 'salnama-theme';
    private $theme_slug = 'salnama-theme';
    
    /**
     * API endpoints
     */
    private $github_api_url;
    private $update_url;
    
    public function __construct() {
        $this->github_api_url = "https://api.github.com/repos/{$this->github_username}/{$this->github_repo}/releases/latest";
        $this->update_url = "https://github.com/{$this->github_username}/{$this->github_repo}/releases/download/";
        
        $this->setup_hooks();
    }

    /**
     * Setup WordPress hooks
     */
    private function setup_hooks() {
        add_filter('pre_set_site_transient_update_themes', [$this, 'check_theme_update']);
        add_filter('upgrader_pre_download', [$this, 'pre_download_filter'], 10, 3);
        add_action('admin_notices', [$this, 'admin_update_notice']);
    }

    /**
     * Check for theme updates
     */
    public function check_theme_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $latest_release = $this->get_latest_release();
        
        if (!$latest_release || is_wp_error($latest_release)) {
            return $transient;
        }

        $current_version = wp_get_theme()->get('Version');
        $latest_version = $latest_release['version'];

        if (version_compare($current_version, $latest_version, '<')) {
            $transient->response[$this->theme_slug] = [
                'theme' => $this->theme_slug,
                'new_version' => $latest_version,
                'package' => $latest_release['download_url'],
                'url' => $latest_release['url']
            ];
        }

        return $transient;
    }

    /**
     * Get latest release from GitHub API
     */
    private function get_latest_release() {
        $cache_key = 'salnama_theme_latest_release';
        $cached_data = get_transient($cache_key);

        if ($cached_data !== false) {
            return $cached_data;
        }

        $response = wp_remote_get($this->github_api_url, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Salnama-Theme-Updater'
            ],
            'timeout' => 10
        ]);

        if (is_wp_error($response)) {
            error_log('Salnama Theme Updater Error: ' . $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['tag_name'])) {
            return false;
        }

        $release_data = [
            'version' => ltrim($data['tag_name'], 'v'),
            'url' => $data['html_url'],
            'download_url' => $this->update_url . $data['tag_name'] . '/salnama-theme.zip',
            'published_at' => $data['published_at'],
            'release_notes' => $data['body'] ?? ''
        ];

        // Cache for 12 hours
        set_transient($cache_key, $release_data, 12 * HOUR_IN_SECONDS);

        return $release_data;
    }

    /**
     * Pre-download filter for additional validation
     */
    public function pre_download_filter($reply, $package, $updater) {
        if (strpos($package, 'salnama-theme') !== false) {
            // Check if theme directory is writable
            $theme_dir = get_template_directory();
            if (!is_writable($theme_dir)) {
                return new \WP_Error(
                    'theme_not_writable',
                    'پوشه قالب قابل نوشتن نیست. لطفا دسترسی‌های سرور را بررسی کنید.'
                );
            }
            
            // Log update attempt
            error_log('Salnama Theme update started: ' . $package);
        }
        
        return $reply;
    }

    /**
     * Admin notice for available updates
     */
    public function admin_update_notice() {
        if (!current_user_can('update_themes')) {
            return;
        }

        $latest_release = $this->get_latest_release();
        if (!$latest_release) {
            return;
        }

        $current_version = wp_get_theme()->get('Version');
        $latest_version = $latest_release['version'];

        if (version_compare($current_version, $latest_version, '<')) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong>قالب سالنمای نو:</strong> 
                    نسخه جدید (<?php echo esc_html($latest_version); ?>) available. 
                    <a href="<?php echo admin_url('update-core.php'); ?>">
                        بروزرسانی کنید
                    </a>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Get update information for debugging
     */
    public function get_update_info() {
        $current_version = wp_get_theme()->get('Version');
        $latest_release = $this->get_latest_release();
        
        return [
            'current_version' => $current_version,
            'latest_version' => $latest_release ? $latest_release['version'] : 'Unknown',
            'update_available' => $latest_release ? version_compare($current_version, $latest_release['version'], '<') : false,
            'last_checked' => get_transient('salnama_theme_latest_release') ? 'Cached' : 'Not cached'
        ];
    }

    /**
     * Run the updater service
     */
    public function run() {
        // Service is already running via hooks setup in constructor
    }
}