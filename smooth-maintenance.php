<?php
/**
 * Plugin Name: Smooth Maintenance
 * Plugin URI:  https://example.com/smooth-maintenance
 * Description: A modern maintenance mode plugin with MVC architecture and React admin interface.
 * Version:     1.0.0
 * Author:      Smooth Maintenance
 * Author URI:  https://example.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: smooth-maintenance
 * Domain Path: /languages
 * Requires at least: 6.2
 * Requires PHP: 8.0
 *
 * @package SmoothMaintenance
 */

defined( 'ABSPATH' ) || exit;

// PHP version check.
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>';
			echo esc_html__( 'Smooth Maintenance requires PHP 8.0 or higher. Please upgrade your PHP version.', 'smooth-maintenance' );
			echo '</p></div>';
		}
	);
	return;
}

// Load autoloader.
require_once __DIR__ . '/autoloader.php';

// Initialize the plugin.
add_action(
	'plugins_loaded',
	function () {
		\SmoothMaintenance\Bootstrap::getInstance()->boot();
	}
);

// Register activation hook.
register_activation_hook(
	__FILE__,
	function () {
		\SmoothMaintenance\Core\Activator::activate();
	}
);

// Register deactivation hook.
register_deactivation_hook(
	__FILE__,
	function () {
		\SmoothMaintenance\Core\Deactivator::deactivate();
	}
);
