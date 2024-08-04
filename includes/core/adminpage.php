<?php
namespace WPCronManager\Core;

class AdminPage {
    private $cron_manager;

    public function __construct(CronManager $cron_manager) {
        $this->cron_manager = $cron_manager;
    }

    public function add_admin_menu() {
        add_management_page(
            'WP Cron Manager',
            'Cron Manager',
            'manage_options',
            'wp-cron-manager',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page() {
        $events = $this->cron_manager->get_cron_events();
        $custom_schedules = $this->cron_manager->get_custom_schedules();
        ?>
        <div class="wrap">
            <h1>WP Cron Manager</h1>

            <!-- Add New Cron Event Form -->
            <h2>Add New Cron Event</h2>
            <form id="add-cron-event-form">
                <table class="form-table">
                    <tr>
                        <th><label for="hook">Hook Name</label></th>
                        <td><input type="text" id="hook" name="hook" required></td>
                    </tr>
                    <tr>
                        <th><label for="args">Arguments (JSON)</label></th>
                        <td><input type="text" id="args" name="args" placeholder='{"key": "value"}'></td>
                    </tr>
                    <tr>
                        <th><label for="schedule">Schedule</label></th>
                        <td>
                            <select id="schedule" name="schedule">
                                <option value="once">Once</option>
                                <option value="hourly">Hourly</option>
                                <option value="twicedaily">Twice Daily</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <?php foreach ($custom_schedules as $name => $schedule): ?>
                                    <option value="<?php echo esc_attr($name); ?>"><?php echo esc_html($schedule['display']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button button-primary" value="Add Cron Event">
                </p>
            </form>

            <!-- Add Custom Schedule Form -->
            <h2>Add Custom Schedule</h2>
            <form id="add-custom-schedule-form">
                <table class="form-table">
                    <tr>
                        <th><label for="schedule-name">Schedule Name</label></th>
                        <td><input type="text" id="schedule-name" name="schedule-name" required></td>
                    </tr>
                    <tr>
                        <th><label for="schedule-interval">Interval (seconds)</label></th>
                        <td><input type="number" id="schedule-interval" name="schedule-interval" required></td>
                    </tr>
                    <tr>
                        <th><label for="schedule-display">Display Name</label></th>
                        <td><input type="text" id="schedule-display" name="schedule-display" required></td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button button-primary" value="Add Custom Schedule">
                </p>
            </form>

            <!-- Custom Schedules Table -->
            <h2>Custom Schedules</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Interval</th>
                    <th>Display</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($custom_schedules as $name => $schedule): ?>
                    <tr>
                        <td><?php echo esc_html($name); ?></td>
                        <td><?php echo esc_html($schedule['interval']); ?> seconds</td>
                        <td><?php echo esc_html($schedule['display']); ?></td>
                        <td>
                            <button class="button delete-custom-schedule" data-name="<?php echo esc_attr($name); ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Cron Events Table -->
            <h2>Current Cron Events</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th>Hook</th>
                    <th>Next Run</th>
                    <th>Schedule</th>
                    <th>Arguments</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo esc_html($event['hook']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $event['time']); ?></td>
                        <td><?php echo esc_html($event['schedule'] ?: 'Once'); ?></td>
                        <td>
                            <?php
                            if (empty($event['args'])) {
                                echo 'No arguments';
                            } else {
                                echo esc_html(json_encode($event['args'], JSON_PRETTY_PRINT));
                            }
                            ?>
                        </td>
                        <td>
                            <button class="button run-cron" data-hook="<?php echo esc_attr($event['hook']); ?>" data-args='<?php echo esc_attr(json_encode($event['args'])); ?>'>Run Now</button>
                            <button class="button delete-cron" data-hook="<?php echo esc_attr($event['hook']); ?>" data-args='<?php echo esc_attr(json_encode($event['args'])); ?>'>Delete</button>
                            <?php if ($event['schedule']): ?>
                                <button class="button pause-cron" data-hook="<?php echo esc_attr($event['hook']); ?>" data-args='<?php echo esc_attr(json_encode($event['args'])); ?>'>Pause</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Export Button -->
            <p>
                <a href="<?php echo admin_url('admin-ajax.php?action=export_cron_events'); ?>" class="button">Export Cron Events (CSV)</a>
            </p>
        </div>
        <?php
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'tools_page_wp-cron-manager') {
            return;
        }

        wp_enqueue_style('wp-cron-manager-styles', plugin_dir_url(__FILE__) . '../../assets/css/admin.css');
        wp_enqueue_script('wp-cron-manager-script', plugin_dir_url(__FILE__) . '../../assets/js/admin.js', ['jquery'], false, true);
        wp_localize_script('wp-cron-manager-script', 'wpCronManager', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp-cron-manager-nonce'),
        ]);
    }
}