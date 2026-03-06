<?php
/**
 * Settings Model.
 *
 * Handles plugin settings stored in wp_options.
 *
 * @package SmoothMaintenance\Models
 */

namespace SmoothMaintenance\Models;

use SmoothMaintenance\Core\BaseModel;
use SmoothMaintenance\Core\Constants;

defined( 'ABSPATH' ) || exit;

class Settings extends BaseModel {

	/**
	 * WordPress option name.
	 *
	 * @var string
	 */
	protected static string $optionName = '';

	/**
	 * Cached settings.
	 *
	 * @var array|null
	 */
	protected static ?array $cache = null;

	/**
	 * Default settings values.
	 *
	 * @var array
	 */
	protected static array $defaults = array(
		'maintenance_mode_enabled' => false,
		'version'                  => '1.0.0',
	);

	/**
	 * Get the option name.
	 *
	 * @return string
	 */
	protected static function getOptionName(): string {
		return Constants::OPTION_NAME;
	}

	/**
	 * Get a setting value.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public static function get( string $key, mixed $default = null ): mixed {
		$settings = self::all();
		return $settings[ $key ] ?? $default ?? ( self::$defaults[ $key ] ?? null );
	}

	/**
	 * Set a setting value.
	 *
	 * @param string $key   Setting key.
	 * @param mixed  $value Setting value.
	 * @return bool
	 */
	public static function set( string $key, mixed $value ): bool {
		$settings         = self::all();
		$settings[ $key ] = $value;

		$result = update_option( self::getOptionName(), $settings );

		// Clear cache.
		self::$cache = null;

		return $result;
	}

	/**
	 * Get all settings.
	 *
	 * @return array
	 */
	public static function all(): array {
		if ( null !== self::$cache ) {
			return self::$cache;
		}

		$settings   = get_option( self::getOptionName(), array() );
		self::$cache = wp_parse_args( $settings, self::$defaults );

		return self::$cache;
	}

	/**
	 * Check if maintenance mode is enabled.
	 *
	 * @return bool
	 */
	public static function isMaintenanceEnabled(): bool {
		return (bool) self::get( 'maintenance_mode_enabled', false );
	}

	/**
	 * Update multiple settings at once.
	 *
	 * @param array $data Settings key-value pairs.
	 * @return bool
	 */
	public static function updateMany( array $data ): bool {
		$settings = self::all();

		foreach ( $data as $key => $value ) {
			$settings[ $key ] = $value;
		}

		$result = update_option( self::getOptionName(), $settings );

		// Clear cache.
		self::$cache = null;

		return $result;
	}

	/**
	 * Validate settings data.
	 *
	 * @param array $data Data to validate.
	 * @return bool
	 */
	public function validate( array $data ): bool {
		if ( isset( $data['maintenance_mode_enabled'] ) && ! is_bool( $data['maintenance_mode_enabled'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Reset cache (useful for testing).
	 *
	 * @return void
	 */
	public static function resetCache(): void {
		self::$cache = null;
	}
}
