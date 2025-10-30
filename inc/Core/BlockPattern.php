inc/Core/BlockPattern.php
<?php
namespace Salnama_Theme\Core;

/**
 * Manages the registration of custom Block Patterns for the Salnama Theme.
 */
class BlockPattern {

    public function run() {
        add_action( 'init', [ $this, 'register_block_patterns' ] );
        add_action( 'init', [ $this, 'register_pattern_categories' ] );
    }

    /**
     * Registers custom categories for patterns.
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
    }

    /**
     * Registers custom patterns by loading files from the patterns directory.
     */
    public function register_block_patterns() {
        $patterns_dir = SALNAMA_THEME_PATH . '/patterns/';
        if ( ! is_dir( $patterns_dir ) ) {
            return;
        }

        $pattern_files = glob( $patterns_dir . '*.php' );

        foreach ( $pattern_files as $pattern_file ) {
            register_block_pattern(
                'salnama/' . basename( $pattern_file, '.php' ),
                require $pattern_file // فایل PHP حاوی آرایه تعریف الگو
            );
        }
    }
}