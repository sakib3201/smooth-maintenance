<?php
/**
 * Plugin Bootstrap.
 *
 * Singleton application container that initializes all services.
 *
 * @package SmoothMaintenance
 */

namespace SmoothMaintenance;

use SmoothMaintenance\Core\Container;
use SmoothMaintenance\Core\Constants;
use SmoothMaintenance\Core\Loader;
use SmoothMaintenance\Core\Router;
use SmoothMaintenance\Models\Settings;
use SmoothMaintenance\Models\Subscriber;
use SmoothMaintenance\Controllers\Api\MaintenanceController;
use SmoothMaintenance\Controllers\Admin\AdminController;
use SmoothMaintenance\Services\AssetManager;
use SmoothMaintenance\Services\MaintenanceService;
use SmoothMaintenance\Middleware\AuthMiddleware;
use SmoothMaintenance\Core\PostTypes;
use SmoothMaintenance\Blocks\CountdownBlock;
use SmoothMaintenance\Blocks\SubscriberFormBlock;

defined( 'ABSPATH' ) || exit;

class Bootstrap {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Service container.
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * Hooks loader.
	 *
	 * @var Loader
	 */
	private Loader $loader;

	/**
	 * Whether the plugin has been booted.
	 *
	 * @var bool
	 */
	private bool $booted = false;

	/**
	 * Private constructor (singleton).
	 */
	private function __construct() {
		$this->container = new Container();
		$this->loader    = new Loader();
	}

	/**
	 * Get singleton instance.
	 *
	 * @return self
	 */
	public static function getInstance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Boot the application.
	 *
	 * @return void
	 */
	public function boot(): void {
		if ( $this->booted ) {
			return;
		}

		$this->registerServices();
		$this->registerRoutes();
		$this->registerHooks();

		$this->loader->run();

		$this->booted = true;
	}

	/**
	 * Register all services in the container.
	 *
	 * @return void
	 */
	private function registerServices(): void {
		// Core services.
		$this->container->instance( Container::class, $this->container );
		$this->container->instance( Loader::class, $this->loader );

		// Models (singletons).
		$this->container->singleton(
			Settings::class,
			function () {
				return new Settings();
			}
		);

		$this->container->singleton(
			Subscriber::class,
			function () {
				return new Subscriber();
			}
		);

		// Middleware.
		$this->container->singleton(
			AuthMiddleware::class,
			function () {
				return new AuthMiddleware();
			}
		);

		$this->container->singleton(
			PostTypes::class,
			function () {
				return new PostTypes();
			}
		);

		$this->container->singleton(
			CountdownBlock::class,
			function () {
				return new CountdownBlock();
			}
		);

		$this->container->singleton(
			SubscriberFormBlock::class,
			function () {
				return new SubscriberFormBlock();
			}
		);

		// Controllers.
		$this->container->bind(
			MaintenanceController::class,
			function ( Container $c ) {
				return new MaintenanceController(
					$c->make( Settings::class )
				);
			}
		);

		$this->container->bind(
			AdminController::class,
			function () {
				return new AdminController();
			}
		);

		// Services.
		$this->container->singleton(
			MaintenanceService::class,
			function ( Container $c ) {
				return new MaintenanceService(
					$c->make( Settings::class )
				);
			}
		);

		// Asset Manager (Performance)
		$this->container->singleton(
			AssetManager::class,
			function ( Container $c ) {
				return new AssetManager();
			}
		);
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	private function registerRoutes(): void {
		$router = new Router( $this->container );

		// Register middleware aliases.
		$router->registerMiddleware( 'auth', AuthMiddleware::class );

		$router->get( 'settings', array( MaintenanceController::class, 'index' ), array( 'auth' ) );
		$router->post( 'settings', array( MaintenanceController::class, 'update' ), array( 'auth' ) );

		// Public subscriber route.
		$router->post( 'subscribe', array( MaintenanceController::class, 'subscribe' ) );

		// Store router and register on rest_api_init.
		$this->container->instance( Router::class, $router );

		$this->loader->addAction(
			'rest_api_init',
			function () {
				$this->container->make( Router::class )->register();
			}
		);
	}

	/**
	 */
	private function registerHooks(): void {
		// Custom Post Types and Blocks.
		$this->loader->addAction(
			'init',
			function () {
				$this->container->make( PostTypes::class )->register();
				$this->container->make( CountdownBlock::class )->register();
				$this->container->make( SubscriberFormBlock::class )->register();
			}
		);

		// Admin menu.
		$this->loader->addAction(
			'admin_menu',
			function () {
				$this->container->make( AdminController::class )->registerMenu();
			}
		);

		// Admin assets (only on plugin page).
		$this->loader->addAction(
			'admin_enqueue_scripts',
			function ( $hook ) {
				$this->container->make( AdminController::class )->enqueueAssets( $hook );
			}
		);

		// Frontend maintenance mode (priority 1 - early).
		$this->loader->addAction(
			'template_redirect',
			function () {
				$maintenance_service = $this->container->make( MaintenanceService::class );
				if ( $maintenance_service->shouldShowMaintenance() ) {
					// Init performance optimizations first.
					$this->container->make( AssetManager::class )->init();
				}
				// Then attempt to render.
				$maintenance_service->render();
			},
			1
		);
	}

	/**
	 * Get the container.
	 *
	 * @return Container
	 */
	public function getContainer(): Container {
		return $this->container;
	}
}
