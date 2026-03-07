<?php
/**
 * Database Manager.
 *
 * Handles table creation and migrations.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Database {

	/**
	 * Create custom database tables.
	 *
	 * @return void
	 */
	public static function createTables(): void {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'sm_subscribers';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			subscribed_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			ip_address varchar(45) DEFAULT NULL,
			user_agent text DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email),
			KEY subscribed_at (subscribed_at)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
		dbDelta( $sql );
	}

	/**
	 * Drop custom database tables.
	 *
	 * @return void
	 */
	public static function dropTables(): void {
		global $wpdb;

		$table_name = $wpdb->prefix . 'sm_subscribers';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
		$wpdb->query(
			$wpdb->prepare( 'DROP TABLE IF EXISTS %i', $table_name )
		);
	}
}
