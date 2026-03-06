<?php
/**
 * Maintenance REST API Controller.
 *
 * Handles settings CRUD via REST API.
 *
 * @package SmoothMaintenance\Controllers\Api
 */

namespace SmoothMaintenance\Controllers\Api;

use SmoothMaintenance\Core\BaseController;
use SmoothMaintenance\Models\Settings;

defined( 'ABSPATH' ) || exit;

class MaintenanceController extends BaseController {

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
	 * GET /settings - Retrieve all settings.
	 *
	 * @param \WP_REST_Request $request The REST request.
	 * @return \WP_REST_Response
	 */
	public function index( \WP_REST_Request $request ): \WP_REST_Response {
		return $this->success( Settings::all() );
	}

	/**
	 * POST /settings - Update settings.
	 *
	 * @param \WP_REST_Request $request The REST request.
	 * @return \WP_REST_Response
	 */
	public function update( \WP_REST_Request $request ): \WP_REST_Response {
		$data = $request->get_json_params();

		// Validate.
		$validation = $this->validate(
			$data,
			array(
				'maintenance_mode_enabled' => 'boolean',
			)
		);

		if ( true !== $validation ) {
			return $this->error( $validation, 400 );
		}

		// Sanitize and update.
		if ( isset( $data['maintenance_mode_enabled'] ) ) {
			Settings::set(
				'maintenance_mode_enabled',
				rest_sanitize_boolean( $data['maintenance_mode_enabled'] )
			);
		}

		return $this->success(
			Settings::all(),
			__( 'Settings updated successfully.', 'smooth-maintenance' )
		);
	}

	/**
	 * Permission callback for routes.
	 *
	 * @return bool
	 */
	public function permissions(): bool {
		return $this->authorize( 'manage_options' );
	}
}
