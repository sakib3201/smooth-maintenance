<?php
/**
 * REST API Router.
 *
 * Laravel-style route registration for WordPress REST API.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Router {

	/**
	 * Registered routes.
	 *
	 * @var array
	 */
	protected array $routes = array();

	/**
	 * The service container.
	 *
	 * @var Container
	 */
	protected Container $container;

	/**
	 * REST namespace.
	 *
	 * @var string
	 */
	protected string $namespace;

	/**
	 * Middleware registry.
	 *
	 * @var array<string, string>
	 */
	protected array $middlewareMap = array();

	/**
	 * Constructor.
	 *
	 * @param Container $container The service container.
	 * @param string    $namespace REST API namespace.
	 */
	public function __construct( Container $container, string $namespace = '' ) {
		$this->container = $container;
		$this->namespace = $namespace ?: Constants::REST_NAMESPACE;
	}

	/**
	 * Register a GET route.
	 *
	 * @param string $route      The route path.
	 * @param array  $controller [ControllerClass, method] pair.
	 * @param array  $middleware Middleware names to apply.
	 * @return self
	 */
	public function get( string $route, array $controller, array $middleware = array() ): self {
		return $this->addRoute( 'GET', $route, $controller, $middleware );
	}

	/**
	 * Register a POST route.
	 *
	 * @param string $route      The route path.
	 * @param array  $controller [ControllerClass, method] pair.
	 * @param array  $middleware Middleware names to apply.
	 * @return self
	 */
	public function post( string $route, array $controller, array $middleware = array() ): self {
		return $this->addRoute( 'POST', $route, $controller, $middleware );
	}

	/**
	 * Register middleware class mapping.
	 *
	 * @param string $name  Middleware alias.
	 * @param string $class Middleware class name.
	 * @return void
	 */
	public function registerMiddleware( string $name, string $class ): void {
		$this->middlewareMap[ $name ] = $class;
	}

	/**
	 * Add a route to the registry.
	 *
	 * @param string $method     HTTP method.
	 * @param string $route      Route path.
	 * @param array  $controller [ControllerClass, method] pair.
	 * @param array  $middleware Middleware names.
	 * @return self
	 */
	protected function addRoute( string $method, string $route, array $controller, array $middleware = array() ): self {
		$this->routes[] = compact( 'method', 'route', 'controller', 'middleware' );
		return $this;
	}

	/**
	 * Register all routes with WordPress REST API.
	 *
	 * Called on rest_api_init hook.
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->routes as $route ) {
			$controller_class  = $route['controller'][0];
			$controller_method = $route['controller'][1];

			$callback = function ( \WP_REST_Request $request ) use ( $controller_class, $controller_method ) {
				$controller = $this->container->make( $controller_class );
				return $controller->$controller_method( $request );
			};

			$permission_callback = $this->buildPermissionCallback( $route['middleware'] );

			register_rest_route(
				$this->namespace,
				'/' . ltrim( $route['route'], '/' ),
				array(
					'methods'             => $route['method'],
					'callback'            => $callback,
					'permission_callback' => $permission_callback,
				)
			);
		}
	}

	/**
	 * Build permission callback from middleware stack.
	 *
	 * @param array $middleware Middleware names.
	 * @return callable
	 */
	protected function buildPermissionCallback( array $middleware ): callable {
		if ( empty( $middleware ) ) {
			return '__return_true';
		}

		return function ( \WP_REST_Request $request ) use ( $middleware ) {
			foreach ( $middleware as $name ) {
				// Support "middleware:param" syntax.
				$parts      = explode( ':', $name );
				$alias      = $parts[0];
				$capability = $parts[1] ?? 'manage_options';

				if ( ! isset( $this->middlewareMap[ $alias ] ) ) {
					continue;
				}

				$middleware_instance = $this->container->make( $this->middlewareMap[ $alias ] );
				$result             = $middleware_instance->handle( $request, $capability );

				if ( $result !== true ) {
					return $result;
				}
			}

			return true;
		};
	}
}
