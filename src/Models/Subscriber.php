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
	 * Get all subscribers with pagination.
	 *
	 * @param int $limit  Max rows to return.
	 * @param int $offset Row offset.
	 * @return array
	 */
	public static function getAll( int $limit = 50, int $offset = 0 ): array {
		global $wpdb;
		$table = static::getTable();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT id, email, subscribed_at, ip_address FROM {$table} ORDER BY subscribed_at DESC LIMIT %d OFFSET %d",
				$limit,
				$offset
			),
			ARRAY_A
		);
		return $rows ?: [];
	}

	/**
	 * Count all subscribers.
	 *
	 * @return int
	 */
	public static function count(): int {
		global $wpdb;
		$table = static::getTable();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		return (int) $wpdb->get_var(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT COUNT(*) FROM {$table}"
		);
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
