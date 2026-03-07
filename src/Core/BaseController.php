<?php
/**
 * Abstract Base Controller.
 *
 * Common controller methods for REST API and admin controllers.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

abstract class BaseController {

	/**
	 * Return a success response.
	 *
	 * @param mixed  $data    Response data.
	 * @param string $message Optional message.
	 * @param int    $code    HTTP status code.
	 * @return \WP_REST_Response
	 */
	protected function success( mixed $data = null, string $message = '', int $code = 200 ): \WP_REST_Response {
		$response = array(
			'success' => true,
		);

		if ( $message ) {
			$response['message'] = $message;
		}

		if ( null !== $data ) {
			$response['data'] = $data;
		}

		return new \WP_REST_Response( $response, $code );
	}

	/**
	 * Return an error response.
	 *
	 * @param string $message Error message.
	 * @param int    $code    HTTP status code.
	 * @return \WP_REST_Response
	 */
	protected function error( string $message = 'An error occurred', int $code = 400 ): \WP_REST_Response {
		return new \WP_REST_Response(
			array(
				'success' => false,
				'message' => $message,
			),
			$code
		);
	}

	/**
	 * Validate request data against rules.
	 *
	 * @param array $data  Data to validate.
	 * @param array $rules Validation rules (key => type).
	 * @return bool|string True if valid, error message string if invalid.
	 */
	protected function validate( array $data, array $rules ): bool|string {
		foreach ( $rules as $key => $type ) {
			if ( ! isset( $data[ $key ] ) ) {
				continue;
			}

			$valid = match ( $type ) {
				'boolean' => is_bool( $data[ $key ] ),
				'string'  => is_string( $data[ $key ] ),
				'integer' => is_int( $data[ $key ] ),
				'email'   => is_email( $data[ $key ] ),
				default   => true,
			};

			if ( ! $valid ) {
				return sprintf(
					/* translators: 1: field name, 2: expected type */
					__( 'Field "%1$s" must be of type %2$s.', 'smooth-maintenance' ),
					$key,
					$type
				);
			}
		}

		return true;
	}

	/**
	 * Check if current user has a capability.
	 *
	 * @param string $capability WordPress capability.
	 * @return bool
	 */
	protected function authorize( string $capability = 'manage_options' ): bool {
		return current_user_can( $capability );
	}
}
