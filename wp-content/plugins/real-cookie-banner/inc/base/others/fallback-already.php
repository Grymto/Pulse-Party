<?php
defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

if (!function_exists('rcb_skip_already_admin_notice')) {
    /**
     * Show an admin notice to administrators when the plugin is already active.
     */
    function rcb_skip_already_admin_notice() {
        if (current_user_can('activate_plugins')) {
            echo '<div class=\'notice notice-error\'>
				<p>Currently multiple versions of the plugin <strong>Real Cookie Banner</strong> are active. Please deactivate all versions except the one you want to use.</p>' .
                '</div>';
        }
    }
}
add_action('admin_notices', 'rcb_skip_already_admin_notice');

if (!function_exists('rcb_skip_already_deactivate_lite')) {
    /**
     * Automatically deactivate lite version when we try to activate the PRO version.
     */
    function rcb_skip_already_deactivate_lite() {
        // Avoid doing this in local stack as we do not have pro and lite difference in slug
        if (defined('DEVOWL_WP_DEV') && constant('DEVOWL_WP_DEV') && $_SERVER['SERVER_PORT'] === strval(10000)) {
            return;
        }

        deactivate_plugins(RCB_SLUG_LITE . '/index.php');
    }
}
register_activation_hook(
    constant('WP_PLUGIN_DIR') . DIRECTORY_SEPARATOR . RCB_SLUG_PRO . DIRECTORY_SEPARATOR . 'index.php',
    'rcb_skip_already_deactivate_lite'
);
