<?php
/**
 * MaintenanceService unit tests.
 *
 * @package SmoothMaintenance\Tests\Unit
 */

namespace SmoothMaintenance\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SmoothMaintenance\Models\Settings;

class MaintenanceServiceTest extends TestCase {

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

	public function test_maintenance_disabled_by_default(): void {
		$this->assertFalse( Settings::isMaintenanceEnabled() );
	}

	public function test_maintenance_can_be_enabled(): void {
		Settings::set( 'maintenance_mode_enabled', true );
		$this->assertTrue( Settings::isMaintenanceEnabled() );
	}

	public function test_maintenance_can_be_disabled(): void {
		Settings::set( 'maintenance_mode_enabled', true );
		Settings::set( 'maintenance_mode_enabled', false );
		$this->assertFalse( Settings::isMaintenanceEnabled() );
	}
}
