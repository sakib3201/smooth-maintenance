<?php
/**
 * Plugin Activator.
 *
 * Runs on plugin activation.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Activator {

	/**
	 * Run activation tasks.
	 *
	 * @return void
	 */
	public static function activate(): void {
		// Create custom database tables.
		Database::createTables();

		// Set default settings.
		self::setDefaults();

		// Store plugin version.
		update_option( 'smooth_maintenance_version', Constants::VERSION );

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Set default plugin settings.
	 *
	 * @return void
	 */
	protected static function setDefaults(): void {
		$defaults = array(
			'maintenance_mode_enabled' => false,
			'version'                  => Constants::VERSION,
		);

		if ( ! get_option( Constants::OPTION_NAME ) ) {
			add_option( Constants::OPTION_NAME, $defaults );
		}
	}
}
