<?php

/**
 * Salnama Theme functions and definitions.
 * * @package Salnama_Theme
 */

// جلوگیری از دسترسی مستقیم به فایل
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. تعریف ثابت‌های اصلی قالب برای استفاده در کلاس‌های OOP
 */
if ( ! defined( 'SALNAMA_THEME_VERSION' ) ) {
    define( 'SALNAMA_THEME_VERSION', '1.0.5' );
}
if ( ! defined( 'SALNAMA_THEME_PATH' ) ) {
    define( 'SALNAMA_THEME_PATH', get_template_directory() );
}
if ( ! defined( 'SALNAMA_THEME_URI' ) ) {
    define( 'SALNAMA_THEME_URI', get_template_directory_uri() );
}
if ( ! defined( 'SALNAMA_INC_PATH' ) ) {
    define( 'SALNAMA_INC_PATH', SALNAMA_THEME_PATH . '/inc' );
}
if ( ! defined( 'SALNAMA_ASSETS_URI' ) ) {
    define( 'SALNAMA_ASSETS_URI', SALNAMA_THEME_URI . '/assets' );
}

/**
 * 2. بارگذاری Autoloader برای مدیریت کلاس‌های OOP
 * تمام کلاس‌های دیگر از این طریق فراخوانی می‌شوند و نیازی به require مستقیم نیست.
 */
require SALNAMA_INC_PATH . '/Autoloader.php';

/**
 * 3. شروع کار: فراخوانی کلاس Init برای ثبت تمام سرویس‌ها
 * این تابع به صورت خودکار توسط Init.php (که بعداً ایجاد می‌شود) فراخوانی می‌شود.
 */
Salnama_Theme\Init::register_services(); // (در مرحله بعد فعال می شود)
