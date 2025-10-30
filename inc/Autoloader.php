<?php
namespace Salnama_Theme;

/**
 * Autoloader for Salnama Theme classes.
 * This class ensures that all OOP classes within the Salnama_Theme namespace 
 * are loaded automatically when they are first used, following PSR-4 logic.
 */
class Autoloader {

    /**
     * Registers the autoload method.
     */
    public static function register() {
        // ثبت تابع بارگذاری خودکار
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    /**
     * Loads the class file.
     *
     * @param string $class The class name to load.
     */
    public static function autoload( $class ) {
        // بررسی می‌کند که آیا کلاس در فضای نام قالب ما قرار دارد یا خیر
        if ( strpos( $class, 'Salnama_Theme\\' ) !== 0 ) {
            return;
        }

        // جایگزینی نام Namespace با پوشه inc و جداکننده‌ها با اسلش
        // مثال: Salnama_Theme\Core\ThemeSetup -> Core/ThemeSetup.php
        $class_name_relative = str_replace( 'Salnama_Theme\\', '', $class );
        $file_name = SALNAMA_INC_PATH . '/' . str_replace( '\\', DIRECTORY_SEPARATOR, $class_name_relative ) . '.php';

        // بارگذاری فایل در صورت وجود
        if ( file_exists( $file_name ) ) {
            require_once $file_name;
        }
    }
}

// ثبت Autoloader بلافاصله پس از تعریف
Autoloader::register();
