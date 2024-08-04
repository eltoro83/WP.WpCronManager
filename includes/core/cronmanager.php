<?php
namespace WPCronManager\Core;

class CronManager {
    public function get_cron_events() {
        $crons = _get_cron_array();
        $events = [];

        foreach ($crons as $time => $cron) {
            foreach ($cron as $hook => $dings) {
                foreach ($dings as $sig => $data) {
                    $events[] = [
                        'time' => $time,
                        'hook' => $hook,
                        'schedule' => isset($data['schedule']) ? $data['schedule'] : false,
                        'args' => isset($data['args']) ? $data['args'] : [],
                    ];
                }
            }
        }

        return $events;
    }

    public function add_cron_event($hook, $args, $schedule) {
        if (empty($args)) {
            $args = [];
        } elseif (is_string($args)) {
            $args = json_decode($args, true);
        }

        if ($schedule === 'once') {
            wp_schedule_single_event(time(), $hook, $args);
        } else {
            wp_schedule_event(time(), $schedule, $hook, $args);
        }
    }

    public function delete_cron_event($hook, $args) {
        wp_clear_scheduled_hook($hook, $args);
    }

    public function pause_cron_event($hook, $args) {
        $timestamp = wp_next_scheduled($hook, $args);
        if ($timestamp) {
            wp_unschedule_event($timestamp, $hook, $args);
            update_option("paused_cron_{$hook}", [
                'args' => $args,
                'timestamp' => $timestamp,
            ]);
        }
    }

    public function resume_cron_event($hook, $args) {
        $paused_cron = get_option("paused_cron_{$hook}");
        if ($paused_cron) {
            wp_schedule_single_event($paused_cron['timestamp'], $hook, $paused_cron['args']);
            delete_option("paused_cron_{$hook}");
        }
    }

    public function run_cron_event($hook, $args) {
        do_action_ref_array($hook, $args);
    }

    public function add_custom_schedules($schedules) {
        $custom_schedules = get_option('wp_cron_manager_custom_schedules', []);
        foreach ($custom_schedules as $name => $schedule) {
            $schedules[$name] = [
                'interval' => $schedule['interval'],
                'display' => $schedule['display']
            ];
        }
        return $schedules;
    }

    public function save_custom_schedule($name, $interval, $display) {
        $custom_schedules = get_option('wp_cron_manager_custom_schedules', []);
        $custom_schedules[$name] = [
            'interval' => $interval,
            'display' => $display
        ];
        update_option('wp_cron_manager_custom_schedules', $custom_schedules);
    }

    public function delete_custom_schedule($name) {
        $custom_schedules = get_option('wp_cron_manager_custom_schedules', []);
        if (isset($custom_schedules[$name])) {
            unset($custom_schedules[$name]);
            update_option('wp_cron_manager_custom_schedules', $custom_schedules);
            return true;
        }
        return false;
    }

    public function get_custom_schedules() {
        return get_option('wp_cron_manager_custom_schedules', []);
    }

    public function handle_ajax_request() {
        check_ajax_referer('wp-cron-manager-nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $action = $_POST['cron_action'];
        $hook = isset($_POST['hook']) ? sanitize_text_field($_POST['hook']) : '';
        $args = isset($_POST['args']) ? sanitize_text_field($_POST['args']) : '[]';
        $args = json_decode($args, true);

        if ($args === null && json_last_error() !== JSON_ERROR_NONE) {
            $args = [];
        }

        switch ($action) {
            case 'add':
                $schedule = sanitize_text_field($_POST['schedule']);
                $this->add_cron_event($hook, $args, $schedule);
                break;
            case 'delete':
                $this->delete_cron_event($hook, $args);
                break;
            case 'pause':
                $this->pause_cron_event($hook, $args);
                break;
            case 'resume':
                $this->resume_cron_event($hook, $args);
                break;
            case 'run':
                $this->run_cron_event($hook, $args);
                break;
            case 'add_custom_schedule':
                $name = $_POST['name'];
                $interval = intval($_POST['interval']);
                $display = $_POST['display'];
                $this->save_custom_schedule($name, $interval, $display);
                break;
            case 'delete_custom_schedule':
                $name = $_POST['name'];
                $this->delete_custom_schedule($name);
                break;
        }

        wp_send_json_success();
    }

    public function export_cron_events() {
        $events = $this->get_cron_events();
        $csv = "Hook,Time,Schedule,Arguments\n";

        foreach ($events as $event) {
            $csv .= sprintf(
                "%s,%s,%s,%s\n",
                $event['hook'],
                date('Y-m-d H:i:s', $event['time']),
                $event['schedule'] ?: 'once',
                json_encode($event['args'])
            );
        }

        return $csv;
    }
}