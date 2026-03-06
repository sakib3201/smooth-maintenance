<?php
/**
 * Maintenance Service.
 *
 * Business logic for maintenance mode display and bypass rules.
 *
 * @package SmoothMaintenance\Services
 */

namespace SmoothMaintenance\Services;

use SmoothMaintenance\Core\Constants;
use SmoothMaintenance\Models\Settings;

defined( 'ABSPATH' ) || exit;

class MaintenanceService {

	/**
	 * Settings model instance.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings model.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Determine if the maintenance page should be shown.
	 *
	 * @return bool
	 */
	public function shouldShowMaintenance(): bool {
		if ( ! Settings::isMaintenanceEnabled() ) {
			return false;
		}

		if ( $this->canBypass( wp_get_current_user() ) ) {
			return false;
		}

		// Don't show on login page.
		if ( $this->isLoginPage() ) {
			return false;
		}

		// Don't show for REST API requests.
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return false;
		}

		// Don't show for AJAX requests.
		if ( wp_doing_ajax() ) {
			return false;
		}

		// Don't show for WP-CLI.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if a user can bypass maintenance mode.
	 *
	 * @param \WP_User $user The user to check.
	 * @return bool
	 */
	public function canBypass( \WP_User $user ): bool {
		// Admins can always bypass.
		if ( user_can( $user, 'manage_options' ) ) {
			return true;
		}

		/**
		 * Filter whether a user can bypass maintenance mode.
		 *
		 * @param bool     $bypass Whether the user can bypass.
		 * @param \WP_User $user   The user being checked.
		 */
		return apply_filters( 'smooth_maintenance_bypass', false, $user );
	}

	/**
	 * Render the maintenance page and exit.
	 *
	 * Called on template_redirect hook.
	 *
	 * @return void
	 */
	public function render(): void {
		if ( ! $this->shouldShowMaintenance() ) {
			return;
		}

		$this->setStatus();

		// Template variables for the view.
		$site_name = get_bloginfo( 'name' );
		$site_url  = home_url();
		$logo_url  = '';
		$message   = __( 'We are currently performing scheduled maintenance. We will be back online shortly.', 'smooth-maintenance' );

		// Check for theme override.
		$template_path = $this->getTemplatePath();

		/**
		 * Filter the maintenance page content variables.
		 *
		 * @param array $vars Template variables.
		 */
		$vars = apply_filters(
			'smooth_maintenance_content',
			compact( 'site_name', 'site_url', 'logo_url', 'message' )
		);

		// Extract variables for the template.
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $vars );

		include $template_path;
		exit;
	}

	/**
	 * Set HTTP 503 status and headers.
	 *
	 * @return void
	 */
	protected function setStatus(): void {
		status_header( 503 );
		header( 'Retry-After: 3600' );
		header( 'Content-Type: text/html; charset=utf-8' );
		nocache_headers();
	}

	/**
	 * Get the maintenance template path.
	 *
	 * Checks for theme override first.
	 *
	 * @return string Template file path.
	 */
	protected function getTemplatePath(): string {
		// Check for theme override.
		$theme_template = get_stylesheet_directory() . '/smooth-maintenance/maintenance.php';

		/**
		 * Filter the maintenance template path.
		 *
		 * @param string $path Template file path.
		 */
		$template = apply_filters( 'smooth_maintenance_template_path', $theme_template );

		if ( file_exists( $template ) ) {
			return $template;
		}

		// Fallback to plugin template.
		return Constants::pluginPath() . 'src/Views/Frontend/maintenance.php';
	}

	/**
	 * Check if current page is the login page.
	 *
	 * @return bool
	 */
	protected function isLoginPage(): bool {
		return in_array(
			$GLOBALS['pagenow'] ?? '',
			array( 'wp-login.php', 'wp-register.php' ),
			true
		);
	}
}
