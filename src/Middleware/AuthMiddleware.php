<?php
/**
 * Authentication Middleware.
 *
 * Capability-based authorization for REST API routes.
 *
 * @package SmoothMaintenance\Middleware
 */

namespace SmoothMaintenance\Middleware;

defined( 'ABSPATH' ) || exit;

class AuthMiddleware {

	/**
	 * Handle the authorization check.
	 *
	 * @param \WP_REST_Request $request    The REST request.
	 * @param string           $capability Required capability.
	 * @return bool|\WP_Error True if authorized, WP_Error otherwise.
	 */
	public function handle( \WP_REST_Request $request, string $capability = 'manage_options' ): bool|\WP_Error {
		if ( ! current_user_can( $capability ) ) {
			return $this->unauthorized();
		}

		return true;
	}

	/**
	 * Return an unauthorized error.
	 *
	 * @return \WP_Error
	 */
	protected function unauthorized(): \WP_Error {
		return new \WP_Error(
			'rest_forbidden',
			__( 'You do not have permission to access this resource.', 'smooth-maintenance' ),
			array( 'status' => 401 )
		);
	}
}
