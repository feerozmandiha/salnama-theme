<?php
namespace Salnama_Theme;

use Salnama_Theme\Core\ThemeSetup;
use Salnama_Theme\Core\AssetsLoader;
use Salnama_Theme\Core\ThemeUpdater; // اضافه کردن کلاس آپدیت

use Salnama_Theme\Core\BlockPattern;

use Salnama_Theme\WooCommerce\WooCommerceSetup; // کلاس آینده

/**
 * Initializes the theme services.
 * This class acts as the central hub (Bootstrapper) to register all 
 * core functionalities of the Salnama Theme using the OOP approach.
 */
class Init {

    /**
     * Retrieves a list of services (classes) that need to be instantiated and run.
     * @return array Array of class names (fully qualified).
     */
    public static function get_services(): array {
        return [
            ThemeSetup::class,
            AssetsLoader::class,
            ThemeUpdater::class, // فعال کردن آپدیت خودکار

            // WooCommerceSetup::class, // ثبت کلاس‌های WooCommerce برای مدیریت تنظیمات فروشگاه
        ];
    }

    /**
     * Registers all service classes by instantiating them and calling the 'run' method.
     */
    public static function register_services() {
        foreach ( self::get_services() as $class ) {
            $service = self::instantiate( $class );
            if ( method_exists( $service, 'run' ) ) {
                $service->run();
            }
        }
    }

    /**
     * Instantiates a class.
     * @param string $class The class name.
     * @return object The instance of the class.
     */
    private static function instantiate( string $class ): object {
        return new $class();
    }
}
