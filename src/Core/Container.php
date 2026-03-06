<?php
/**
 * Service Container - Dependency Injection.
 *
 * Lightweight Laravel-inspired IoC container.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Container {

	/**
	 * Registered bindings.
	 *
	 * @var array<string, callable>
	 */
	protected array $bindings = array();

	/**
	 * Singleton instances.
	 *
	 * @var array<string, object>
	 */
	protected array $instances = array();

	/**
	 * Singleton flags.
	 *
	 * @var array<string, bool>
	 */
	protected array $singletons = array();

	/**
	 * Register a binding.
	 *
	 * @param string   $abstract The abstract identifier.
	 * @param callable $concrete The factory closure.
	 * @return void
	 */
	public function bind( string $abstract, callable $concrete ): void {
		$this->bindings[ $abstract ] = $concrete;
	}

	/**
	 * Register a singleton binding.
	 *
	 * @param string   $abstract The abstract identifier.
	 * @param callable $concrete The factory closure.
	 * @return void
	 */
	public function singleton( string $abstract, callable $concrete ): void {
		$this->bindings[ $abstract ]  = $concrete;
		$this->singletons[ $abstract ] = true;
	}

	/**
	 * Resolve a service from the container.
	 *
	 * @param string $abstract The abstract identifier.
	 * @return mixed The resolved instance.
	 * @throws \RuntimeException If the binding is not found.
	 */
	public function make( string $abstract ): mixed {
		// Return existing singleton instance.
		if ( isset( $this->instances[ $abstract ] ) ) {
			return $this->instances[ $abstract ];
		}

		if ( ! isset( $this->bindings[ $abstract ] ) ) {
			throw new \RuntimeException( "No binding found for: {$abstract}" );
		}

		$concrete = $this->bindings[ $abstract ];
		$instance = $concrete( $this );

		// Cache if singleton.
		if ( isset( $this->singletons[ $abstract ] ) ) {
			$this->instances[ $abstract ] = $instance;
		}

		return $instance;
	}

	/**
	 * Check if a binding exists.
	 *
	 * @param string $abstract The abstract identifier.
	 * @return bool
	 */
	public function has( string $abstract ): bool {
		return isset( $this->bindings[ $abstract ] ) || isset( $this->instances[ $abstract ] );
	}

	/**
	 * Set an existing instance.
	 *
	 * @param string $abstract The abstract identifier.
	 * @param mixed  $instance The instance to store.
	 * @return void
	 */
	public function instance( string $abstract, mixed $instance ): void {
		$this->instances[ $abstract ] = $instance;
	}
}
