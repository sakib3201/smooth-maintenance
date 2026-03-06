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

		$template_id = Settings::get( 'active_template' );
		$post        = get_post( $template_id );
		$content     = '';

		if ( $post && \SmoothMaintenance\Core\PostTypes::TEMPLATE_CPT === $post->post_type ) {
			// Process Gutenberg blocks into HTML.
			$content = do_blocks( $post->post_content );
			// Process shortcodes and other core filters.
			$content = apply_filters( 'the_content', $content );
		} else {
			// Fallback if no valid template is set or found.
			$content = '<h1>' . esc_html__( 'Maintenance Mode', 'smooth-maintenance' ) . '</h1><p>' . esc_html__( 'We will be back shortly.', 'smooth-maintenance' ) . '</p>';
		}

		$title = sprintf(
			/* translators: %s: Site name */
			__( 'Maintenance - %s', 'smooth-maintenance' ),
			get_bloginfo( 'name' )
		);

		/**
		 * Filter the fully rendered HTML content before output.
		 *
		 * @param string $content HTML content.
		 */
		$html_content = apply_filters( 'smooth_maintenance_html', $content );

		// Load the clean canvas shell.
		include Constants::pluginPath() . 'src/Views/Frontend/canvas.php';
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
