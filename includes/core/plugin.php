<?php
namespace WPCronManager\Core;

class Plugin {
    private $cron_manager;
    private $admin_page;

    public function __construct() {
        $this->cron_manager = new CronManager();
        $this->admin_page = new AdminPage($this->cron_manager);
    }

    public function run() {
        // Hook into WordPress
        add_action('admin_menu', [$this->admin_page, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this->admin_page, 'enqueue_scripts']);
        add_action('wp_ajax_wp_cron_manager_action', [$this->cron_manager, 'handle_ajax_request']);

        // Add filter for custom cron schedules
        add_filter('cron_schedules', [$this->cron_manager, 'add_custom_schedules']);

        // Add action for CSV export
        add_action('wp_ajax_export_cron_events', [$this, 'export_cron_events']);

        // Initialize custom schedules
        $this->initialize_custom_schedules();
    }

    private function initialize_custom_schedules() {
        $custom_schedules = get_option('wp_cron_manager_custom_schedules', []);
        if (empty($custom_schedules)) {
            $default_schedules = [
                'every_5_minutes' => [
                    'interval' => 300,
                    'display' => 'Every 5 Minutes'
                ],
                'every_10_minutes' => [
                    'interval' => 600,
                    'display' => 'Every 10 Minutes'
                ]
            ];
            update_option('wp_cron_manager_custom_schedules', $default_schedules);
        }
    }

    public function export_cron_events() {
        // Check for user capabilities
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        // Generate CSV content
        $csv_content = $this->cron_manager->export_cron_events();

        // Set headers for file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="cron_events_export.csv"');

        // Output CSV content
        echo $csv_content;
        exit;
    }
}