<?php
/**
 * Preview Service.
 *
 * Handles preview requests for maintenance templates from the block editor.
 *
 * @package SmoothMaintenance\Services
 */

namespace SmoothMaintenance\Services;

use SmoothMaintenance\Core\Constants;
use SmoothMaintenance\Core\PostTypes;

defined( 'ABSPATH' ) || exit;

class PreviewService {

	/**
	 * Handle preview request.
	 *
	 * @return void
	 */
	public function handle(): void {
		if ( empty( $_GET['sm_preview'] ) ) {
			return;
		}

		if ( ! isset( $_GET['_wpnonce'] )
			|| ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'sm_preview' )
			|| ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Invalid preview request.', 'smooth-maintenance' ), 403 );
		}

		$template_id = isset( $_GET['sm_tid'] ) ? absint( $_GET['sm_tid'] ) : 0;

		if ( ! $template_id ) {
			$this->renderFallback();
			return;
		}

		$post = get_post( $template_id );

		if ( ! $post || PostTypes::TEMPLATE_CPT !== $post->post_type ) {
			$this->renderFallback();
			return;
		}

		$content = do_blocks( $post->post_content );
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$content = apply_filters( 'the_content', $content );

		$html_content = apply_filters( 'smooth_maintenance_html', $content );

		$title = sprintf(
			/* translators: %s: Template title */
			__( 'Preview: %s', 'smooth-maintenance' ),
			get_the_title( $post )
		);

		status_header( 200 );
		header( 'Content-Type: text/html; charset=utf-8' );
		nocache_headers();

		include Constants::pluginPath() . 'src/Views/Frontend/canvas.php';
		exit;
	}

	/**
	 * Render fallback content when no valid template is found.
	 *
	 * @return void
	 */
	private function renderFallback(): void {
		$content = '<h1>' . esc_html__( 'Maintenance Mode', 'smooth-maintenance' ) . '</h1><p>' . esc_html__( 'We will be back shortly.', 'smooth-maintenance' ) . '</p>';

		$html_content = apply_filters( 'smooth_maintenance_html', $content );

		$title = sprintf(
			/* translators: %s: Site name */
			__( 'Maintenance - %s', 'smooth-maintenance' ),
			get_bloginfo( 'name' )
		);

		status_header( 200 );
		header( 'Content-Type: text/html; charset=utf-8' );
		nocache_headers();

		include Constants::pluginPath() . 'src/Views/Frontend/canvas.php';
		exit;
	}
}
