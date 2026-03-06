<?php
/**
 * Settings model unit tests.
 *
 * @package SmoothMaintenance\Tests\Unit
 */

namespace SmoothMaintenance\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SmoothMaintenance\Models\Settings;

class SettingsTest extends TestCase {

	protected function setUp(): void {
		global $wp_test_options;
		$wp_test_options = array();
		Settings::resetCache();
	}

	protected function tearDown(): void {
		global $wp_test_options;
		$wp_test_options = array();
		Settings::resetCache();
	}

	public function test_get_returns_default_when_empty(): void {
		$value = Settings::get( 'maintenance_mode_enabled', false );
		$this->assertFalse( $value );
	}

	public function test_set_and_get(): void {
		Settings::set( 'maintenance_mode_enabled', true );
		$this->assertTrue( Settings::get( 'maintenance_mode_enabled' ) );
	}

	public function test_all_returns_defaults(): void {
		$all = Settings::all();
		$this->assertIsArray( $all );
		$this->assertArrayHasKey( 'maintenance_mode_enabled', $all );
		$this->assertArrayHasKey( 'version', $all );
	}

	public function test_is_maintenance_enabled_defaults_to_false(): void {
		$this->assertFalse( Settings::isMaintenanceEnabled() );
	}

	public function test_is_maintenance_enabled_after_enabling(): void {
		Settings::set( 'maintenance_mode_enabled', true );
		$this->assertTrue( Settings::isMaintenanceEnabled() );
	}

	public function test_update_many(): void {
		Settings::updateMany( array(
			'maintenance_mode_enabled' => true,
			'version'                  => '2.0.0',
		) );

		$this->assertTrue( Settings::get( 'maintenance_mode_enabled' ) );
		$this->assertEquals( '2.0.0', Settings::get( 'version' ) );
	}

	public function test_validate_rejects_non_boolean(): void {
		$settings = new Settings();
		$this->assertFalse( $settings->validate( array( 'maintenance_mode_enabled' => 'yes' ) ) );
	}

	public function test_validate_accepts_boolean(): void {
		$settings = new Settings();
		$this->assertTrue( $settings->validate( array( 'maintenance_mode_enabled' => true ) ) );
	}

	public function test_reset_cache_clears_cache(): void {
		Settings::all(); // Populate cache.
		Settings::resetCache();
		// After reset, should re-read from options.
		$all = Settings::all();
		$this->assertIsArray( $all );
	}
}
