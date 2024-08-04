jQuery(document).ready(function($) {
    // Add New Cron Event
    $('#add-cron-event-form').on('submit', function(e) {
        e.preventDefault();
        var hook = $('#hook').val();
        var args = $('#args').val();
        var schedule = $('#schedule').val();

        $.ajax({
            url: wpCronManager.ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_cron_manager_action',
                nonce: wpCronManager.nonce,
                cron_action: 'add',
                hook: hook,
                args: args,
                schedule: schedule
            },
            success: function(response) {
                if (response.success) {
                    alert('Cron event added successfully!');
                    location.reload();
                } else {
                    alert('Error adding cron event.');
                }
            }
        });
    });

    // Run Cron Event
    $('.run-cron').on('click', function() {
        var hook = $(this).data('hook');
        var args = $(this).data('args');

        $.ajax({
            url: wpCronManager.ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_cron_manager_action',
                nonce: wpCronManager.nonce,
                cron_action: 'run',
                hook: hook,
                args: JSON.stringify(args)
            },
            success: function(response) {
                if (response.success) {
                    alert('Cron event executed successfully!');
                } else {
                    alert('Error executing cron event.');
                }
            }
        });
    });

    // Delete Cron Event
    $('.delete-cron').on('click', function() {
        if (!confirm('Are you sure you want to delete this cron event?')) {
            return;
        }

        var hook = $(this).data('hook');
        var args = $(this).data('args');

        $.ajax({
            url: wpCronManager.ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_cron_manager_action',
                nonce: wpCronManager.nonce,
                cron_action: 'delete',
                hook: hook,
                args: JSON.stringify(args)
            },
            success: function(response) {
                if (response.success) {
                    alert('Cron event deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting cron event.');
                }
            }
        });
    });

    // Pause Cron Event
    $('.pause-cron').on('click', function() {
        var hook = $(this).data('hook');
        var args = $(this).data('args');

        $.ajax({
            url: wpCronManager.ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_cron_manager_action',
                nonce: wpCronManager.nonce,
                cron_action: 'pause',
                hook: hook,
                args: JSON.stringify(args)
            },
            success: function(response) {
                if (response.success) {
                    alert('Cron event paused successfully!');
                    location.reload();
                } else {
                    alert('Error pausing cron event.');
                }
            }
        });
    });

    // Add Custom Schedule
    $('#add-custom-schedule-form').on('submit', function(e) {
        e.preventDefault();
        var name = $('#schedule-name').val();
        var interval = $('#schedule-interval').val();
        var display = $('#schedule-display').val();

        $.ajax({
            url: wpCronManager.ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_cron_manager_action',
                nonce: wpCronManager.nonce,
                cron_action: 'add_custom_schedule',
                name: name,
                interval: interval,
                display: display
            },
            success: function(response) {
                if (response.success) {
                    alert('Custom schedule added successfully!');
                    location.reload();
                } else {
                    alert('Error adding custom schedule.');
                }
            }
        });
    });

    // Delete Custom Schedule
    $('.delete-custom-schedule').on('click', function() {
        if (!confirm('Are you sure you want to delete this custom schedule?')) {
            return;
        }

        var name = $(this).data('name');

        $.ajax({
            url: wpCronManager.ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_cron_manager_action',
                nonce: wpCronManager.nonce,
                cron_action: 'delete_custom_schedule',
                name: name
            },
            success: function(response) {
                if (response.success) {
                    alert('Custom schedule deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting custom schedule.');
                }
            }
        });
    });
});