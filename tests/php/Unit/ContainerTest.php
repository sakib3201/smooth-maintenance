<?php
/**
 * Container unit tests.
 *
 * @package SmoothMaintenance\Tests\Unit
 */

namespace SmoothMaintenance\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SmoothMaintenance\Core\Container;

class ContainerTest extends TestCase {

	private Container $container;

	protected function setUp(): void {
		$this->container = new Container();
	}

	public function test_bind_and_make(): void {
		$this->container->bind( 'test', function () {
			return new \stdClass();
		} );

		$instance = $this->container->make( 'test' );
		$this->assertInstanceOf( \stdClass::class, $instance );
	}

	public function test_singleton_returns_same_instance(): void {
		$this->container->singleton( 'singleton_test', function () {
			$obj       = new \stdClass();
			$obj->id   = uniqid();
			return $obj;
		} );

		$first  = $this->container->make( 'singleton_test' );
		$second = $this->container->make( 'singleton_test' );

		$this->assertSame( $first, $second );
		$this->assertSame( $first->id, $second->id );
	}

	public function test_bind_returns_new_instance_each_time(): void {
		$this->container->bind( 'factory_test', function () {
			$obj     = new \stdClass();
			$obj->id = uniqid();
			return $obj;
		} );

		$first  = $this->container->make( 'factory_test' );
		$second = $this->container->make( 'factory_test' );

		$this->assertNotSame( $first, $second );
	}

	public function test_has_returns_true_for_bound_service(): void {
		$this->container->bind( 'exists', function () {
			return new \stdClass();
		} );

		$this->assertTrue( $this->container->has( 'exists' ) );
		$this->assertFalse( $this->container->has( 'not_exists' ) );
	}

	public function test_instance_sets_existing_object(): void {
		$obj = new \stdClass();
		$obj->name = 'test';

		$this->container->instance( 'direct', $obj );
		$resolved = $this->container->make( 'direct' );

		$this->assertSame( $obj, $resolved );
		$this->assertEquals( 'test', $resolved->name );
	}

	public function test_make_throws_for_unbound(): void {
		$this->expectException( \RuntimeException::class );
		$this->container->make( 'unbound' );
	}

	public function test_container_passed_to_factory(): void {
		$this->container->singleton( 'dep', function () {
			return new \stdClass();
		} );

		$this->container->bind( 'consumer', function ( Container $c ) {
			$obj      = new \stdClass();
			$obj->dep = $c->make( 'dep' );
			return $obj;
		} );

		$consumer = $this->container->make( 'consumer' );
		$this->assertInstanceOf( \stdClass::class, $consumer->dep );
	}
}
