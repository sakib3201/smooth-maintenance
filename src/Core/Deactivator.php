<?php
/**
 * Plugin Deactivator.
 *
 * Runs on plugin deactivation.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Deactivator {

	/**
	 * Run deactivation tasks.
	 *
	 * Note: Does NOT drop tables or delete options.
	 * That should happen on uninstall only.
	 *
	 * @return void
	 */
	public static function deactivate(): void {
		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}
