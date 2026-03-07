<?php
/**
 * Abstract Base Model.
 *
 * Lightweight model base class with query builder methods.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

abstract class BaseModel {

	/**
	 * Model attributes.
	 *
	 * @var array
	 */
	protected array $attributes = array();

	/**
	 * Table name (for custom table models).
	 *
	 * @var string
	 */
	protected static string $table = '';

	/**
	 * Primary key column.
	 *
	 * @var string
	 */
	protected static string $primaryKey = 'id';

	/**
	 * Fillable attributes.
	 *
	 * @var array
	 */
	protected static array $fillable = array();

	/**
	 * Constructor.
	 *
	 * @param array $attributes Initial attributes.
	 */
	public function __construct( array $attributes = array() ) {
		$this->fill( $attributes );
	}

	/**
	 * Fill the model with attributes.
	 *
	 * @param array $attributes Attributes to set.
	 * @return self
	 */
	public function fill( array $attributes ): self {
		foreach ( $attributes as $key => $value ) {
			if ( empty( static::$fillable ) || in_array( $key, static::$fillable, true ) ) {
				$this->attributes[ $key ] = $value;
			}
		}
		return $this;
	}

	/**
	 * Get an attribute value.
	 *
	 * @param string $key     Attribute name.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public function getAttribute( string $key, mixed $default = null ): mixed {
		return $this->attributes[ $key ] ?? $default;
	}

	/**
	 * Set an attribute value.
	 *
	 * @param string $key   Attribute name.
	 * @param mixed  $value Attribute value.
	 * @return void
	 */
	public function setAttribute( string $key, mixed $value ): void {
		$this->attributes[ $key ] = $value;
	}

	/**
	 * Magic getter.
	 *
	 * @param string $key Attribute name.
	 * @return mixed
	 */
	public function __get( string $key ): mixed {
		return $this->getAttribute( $key );
	}

	/**
	 * Magic setter.
	 *
	 * @param string $key   Attribute name.
	 * @param mixed  $value Attribute value.
	 * @return void
	 */
	public function __set( string $key, mixed $value ): void {
		$this->setAttribute( $key, $value );
	}

	/**
	 * Get all attributes.
	 *
	 * @return array
	 */
	public function toArray(): array {
		return $this->attributes;
	}

	/**
	 * Get the full table name with prefix.
	 *
	 * @return string
	 */
	public static function getTable(): string {
		global $wpdb;
		return $wpdb->prefix . static::$table;
	}

	/**
	 * Find a record by primary key.
	 *
	 * @param int|string $id The primary key value.
	 * @return static|null
	 */
	public static function find( int|string $id ): ?static {
		global $wpdb;

		$table = static::getTable();
		$pk    = static::$primaryKey;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE %i = %d',
				$table,
				$pk,
				$id
			),
			ARRAY_A
		);

		if ( ! $row ) {
			return null;
		}

		return new static( $row );
	}

	/**
	 * Save the model to database.
	 *
	 * @return bool
	 */
	public function save(): bool {
		global $wpdb;

		$table = static::getTable();
		$pk    = static::$primaryKey;

		if ( isset( $this->attributes[ $pk ] ) ) {
			// Update existing record.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$result = $wpdb->update(
				$table,
				$this->attributes,
				array( $pk => $this->attributes[ $pk ] )
			);
			return false !== $result;
		}

		// Insert new record.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert( $table, $this->attributes );

		if ( $result ) {
			$this->attributes[ $pk ] = $wpdb->insert_id;
			return true;
		}

		return false;
	}

	/**
	 * Delete the model from database.
	 *
	 * @return bool
	 */
	public function delete(): bool {
		global $wpdb;

		$table = static::getTable();
		$pk    = static::$primaryKey;

		if ( ! isset( $this->attributes[ $pk ] ) ) {
			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->delete(
			$table,
			array( $pk => $this->attributes[ $pk ] )
		);

		return false !== $result;
	}

	/**
	 * Validate model data.
	 *
	 * Override in child classes.
	 *
	 * @param array $data Data to validate.
	 * @return bool
	 */
	public function validate( array $data ): bool {
		return true;
	}
}
