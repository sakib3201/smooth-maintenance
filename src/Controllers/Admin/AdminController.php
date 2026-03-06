<?php
/**
 * Admin Page Controller.
 *
 * Handles admin menu registration, asset enqueuing, and view rendering.
 *
 * @package SmoothMaintenance\Controllers\Admin
 */

namespace SmoothMaintenance\Controllers\Admin;

use SmoothMaintenance\Core\BaseController;
use SmoothMaintenance\Core\Constants;

defined( 'ABSPATH' ) || exit;

class AdminController extends BaseController {

	/**
	 * Admin page hook suffix.
	 *
	 * @var string
	 */
	private string $hookSuffix = '';

	/**
	 * Register admin menu page.
	 *
	 * @return void
	 */
	public function registerMenu(): void {
		$this->hookSuffix = add_menu_page(
			__( 'Smooth Maintenance', 'smooth-maintenance' ),
			__( 'Maintenance', 'smooth-maintenance' ),
			'manage_options',
			Constants::SLUG,
			array( $this, 'index' ),
			'dashicons-hammer',
			80
		);
	}

	/**
	 * Render admin page view.
	 *
	 * @return void
	 */
	public function index(): void {
		include Constants::pluginPath() . 'src/Views/Admin/index.php';
	}

	/**
	 * Enqueue admin assets.
	 *
	 * Only loads on the plugin's admin page.
	 *
	 * @param string $hook The current admin page hook suffix.
	 * @return void
	 */
	public function enqueueAssets( string $hook ): void {
		// Only load on our admin page.
		if ( 'toplevel_page_' . Constants::SLUG !== $hook ) {
			return;
		}

		$asset_file = Constants::pluginPath() . 'build/index.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		// Enqueue JS.
		wp_enqueue_script(
			'smooth-maintenance-admin-js',
			Constants::pluginUrl() . 'build/index.js',
			$asset['dependencies'] ?? array(),
			$asset['version'] ?? Constants::VERSION,
			true
		);

		// Enqueue CSS.
		$css_file = Constants::pluginPath() . 'build/index.css';
		if ( file_exists( $css_file ) ) {
			wp_enqueue_style(
				'smooth-maintenance-admin-css',
				Constants::pluginUrl() . 'build/index.css',
				array( 'wp-components' ),
				$asset['version'] ?? Constants::VERSION
			);
		}

		// Localize script (pass data to React app).
		wp_localize_script(
			'smooth-maintenance-admin-js',
			'smoothMaintenanceAdmin',
			array(
				'restUrl'  => esc_url_raw( rest_url( Constants::REST_NAMESPACE ) ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
				'version'  => Constants::VERSION,
				'adminUrl' => admin_url(),
			)
		);
	}
}
