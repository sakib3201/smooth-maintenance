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
			__( 'Smooth Maintenance', 'smooth-maintenance' ),
			'manage_options',
			Constants::SLUG,
			array( $this, 'index' ),
			'dashicons-hammer',
			80
		);

		// Add explicit Dashboard submenu to ensure it's the target for the parent click.
		add_submenu_page(
			Constants::SLUG,
			__( 'Smooth Maintenance Dashboard', 'smooth-maintenance' ),
			__( 'Dashboard', 'smooth-maintenance' ),
			'manage_options',
			Constants::SLUG,
			array( $this, 'index' )
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

		$asset_file = Constants::pluginPath() . 'build/admin.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		// Enqueue JS.
		wp_enqueue_script(
			'smooth-maintenance-admin-js',
			Constants::pluginUrl() . 'build/admin.js',
			$asset['dependencies'] ?? array(),
			$asset['version'] ?? Constants::VERSION,
			true
		);

		// Enqueue CSS.
		$css_file = Constants::pluginPath() . 'build/admin.css';
		if ( file_exists( $css_file ) ) {
			wp_enqueue_style(
				'smooth-maintenance-admin-css',
				Constants::pluginUrl() . 'build/admin.css',
				array( 'wp-components' ),
				$asset['version'] ?? Constants::VERSION
			);
		}

		// Localize script (pass data to React app).
		wp_localize_script(
			'smooth-maintenance-admin-js',
			'smoothMaintenanceAdmin',
			array(
				'restUrl'    => esc_url_raw( rest_url( Constants::REST_NAMESPACE ) ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'version'    => Constants::VERSION,
				'adminUrl'   => admin_url(),
				'pluginUrl'  => Constants::pluginUrl(),
				'wpAdminUrl' => admin_url(),
			)
		);

		// Hide WordPress standard admin elements for a SAAS experience.
		add_action( 'admin_head', array( $this, 'hideWordPressUI' ) );
		add_filter( 'admin_body_class', array( $this, 'addAdminBodyClass' ) );
	}

	/**
	 * Inject CSS to hide standard WordPress admin elements.
	 *
	 * @return void
	 */
	public function hideWordPressUI(): void {
		echo '
		<style id="sm-fullscreen-admin-style">
			#adminmenuwrap, #adminmenuback, #wpadminbar, #screen-meta-links, #wpfooter {
				display: none !important;
			}
			#wpcontent, #wpbody-content {
				margin-left: 0 !important;
				padding: 0 !important;
			}
			#wpbody {
				padding: 0 !important;
			}
			.notice, .updated, .error {
				display: none !important; /* Managed by plugin app */
			}
			html.wp-toolbar {
				padding-top: 0 !important;
			}
		</style>';
	}

	/**
	 * Add custom class to admin body.
	 *
	 * @param string $classes Current body classes.
	 * @return string Modified body classes.
	 */
	public function addAdminBodyClass( string $classes ): string {
		return $classes . ' sm-saas-admin ';
	}
}
