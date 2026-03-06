<?php
/**
 * Subscriber Model.
 *
 * Handles email subscribers stored in custom table.
 * Foundation for future iterations.
 *
 * @package SmoothMaintenance\Models
 */

namespace SmoothMaintenance\Models;

use SmoothMaintenance\Core\BaseModel;

defined( 'ABSPATH' ) || exit;

class Subscriber extends BaseModel {

	/**
	 * Table name (without prefix).
	 *
	 * @var string
	 */
	protected static string $table = 'sm_subscribers';

	/**
	 * Primary key.
	 *
	 * @var string
	 */
	protected static string $primaryKey = 'id';

	/**
	 * Fillable attributes.
	 *
	 * @var array
	 */
	protected static array $fillable = array(
		'email',
		'subscribed_at',
		'ip_address',
		'user_agent',
	);

	/**
	 * Find a subscriber by email.
	 *
	 * @param string $email Email address.
	 * @return static|null
	 */
	public static function findByEmail( string $email ): ?static {
		global $wpdb;

		$table = static::getTable();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$row = $wpdb->get_row(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT * FROM {$table} WHERE email = %s",
				$email
			),
			ARRAY_A
		);

		if ( ! $row ) {
			return null;
		}

		return new static( $row );
	}

	/**
	 * Create a new subscriber.
	 *
	 * @param array $data Subscriber data.
	 * @return static|null Created subscriber or null on failure.
	 */
	public static function create( array $data ): ?static {
		$subscriber = new static( $data );

		if ( ! $subscriber->validate( $data ) ) {
			return null;
		}

		if ( $subscriber->save() ) {
			return $subscriber;
		}

		return null;
	}

	/**
	 * Validate subscriber data.
	 *
	 * @param array $data Data to validate.
	 * @return bool
	 */
	public function validate( array $data ): bool {
		if ( empty( $data['email'] ) || ! is_email( $data['email'] ) ) {
			return false;
		}

		return true;
	}
}
