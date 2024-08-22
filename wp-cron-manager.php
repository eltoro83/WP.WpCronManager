<?php
/**
 * Plugin Name: WP Cron Manager
 * Description: Manage WordPress cron events - view, edit, delete, pause, resume, and add new events.
 * Version: 1.0
 * Author: Pascal Stier
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Autoloader function
spl_autoload_register(function ($class) {
    $prefix = 'WPCronManager\\';
    $base_dir = plugin_dir_path(__FILE__) . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
function wp_cron_manager_init() {
    $plugin = new WPCronManager\Core\Plugin();
    $plugin->run();
}
add_action('plugins_loaded', 'wp_cron_manager_init');
