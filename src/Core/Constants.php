<?php
/**
 * Plugin Constants.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Constants {

	public const VERSION     = '1.0.0';
	public const SLUG        = 'smooth-maintenance';
	public const OPTION_NAME = 'smooth_maintenance_settings';
	public const REST_NAMESPACE = 'smooth-maintenance/v1';
	public const TEXT_DOMAIN = 'smooth-maintenance';

	/**
	 * Get the plugin directory path.
	 *
	 * @return string
	 */
	public static function pluginPath(): string {
		return plugin_dir_path( dirname( __DIR__ ) );
	}

	/**
	 * Get the plugin URL.
	 *
	 * @return string
	 */
	public static function pluginUrl(): string {
		return plugin_dir_url( dirname( __DIR__ ) );
	}

	/**
	 * Get the plugin basename.
	 *
	 * @return string
	 */
	public static function pluginBasename(): string {
		return plugin_basename( self::pluginPath() . 'smooth-maintenance.php' );
	}
}
