<?php
/**
 * WordPress Hooks Manager.
 *
 * Centralized registration of actions and filters.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Loader {

	/**
	 * Registered actions.
	 *
	 * @var array
	 */
	protected array $actions = array();

	/**
	 * Registered filters.
	 *
	 * @var array
	 */
	protected array $filters = array();

	/**
	 * Register an action hook.
	 *
	 * @param string   $hook     The WordPress hook name.
	 * @param callable $callback The callback function.
	 * @param int      $priority Hook priority.
	 * @param int      $args     Number of arguments.
	 * @return void
	 */
	public function addAction( string $hook, callable $callback, int $priority = 10, int $args = 1 ): void {
		$this->actions[] = compact( 'hook', 'callback', 'priority', 'args' );
	}

	/**
	 * Register a filter hook.
	 *
	 * @param string   $hook     The WordPress hook name.
	 * @param callable $callback The callback function.
	 * @param int      $priority Hook priority.
	 * @param int      $args     Number of arguments.
	 * @return void
	 */
	public function addFilter( string $hook, callable $callback, int $priority = 10, int $args = 1 ): void {
		$this->filters[] = compact( 'hook', 'callback', 'priority', 'args' );
	}

	/**
	 * Execute all registered hooks.
	 *
	 * @return void
	 */
	public function run(): void {
		foreach ( $this->actions as $action ) {
			add_action(
				$action['hook'],
				$action['callback'],
				$action['priority'],
				$action['args']
			);
		}

		foreach ( $this->filters as $filter ) {
			add_filter(
				$filter['hook'],
				$filter['callback'],
				$filter['priority'],
				$filter['args']
			);
		}
	}
}
