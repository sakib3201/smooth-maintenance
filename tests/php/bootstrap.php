<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package SmoothMaintenance\Tests
 */

// Load Composer autoloader if available.
$autoloader = dirname( __DIR__, 2 ) . '/vendor/autoload.php';
if ( file_exists( $autoloader ) ) {
	require_once $autoloader;
}

// Load the plugin autoloader.
require_once dirname( __DIR__, 2 ) . '/autoloader.php';

// Load WordPress test environment if available.
$wp_tests_dir = getenv( 'WP_TESTS_DIR' ) ?: '/tmp/wordpress-tests-lib';

if ( file_exists( $wp_tests_dir . '/includes/functions.php' ) ) {
	require_once $wp_tests_dir . '/includes/functions.php';

	// Load the plugin.
	tests_add_filter(
		'muplugins_loaded',
		function () {
			require dirname( __DIR__, 2 ) . '/smooth-maintenance.php';
		}
	);

	require_once $wp_tests_dir . '/includes/bootstrap.php';
} else {
	// Minimal mock for standalone unit tests.
	if ( ! defined( 'ABSPATH' ) ) {
		define( 'ABSPATH', '/tmp/wordpress/' );
	}

	// Mock essential WordPress functions for unit tests.
	if ( ! function_exists( 'get_option' ) ) {
		function get_option( $option, $default = false ) {
			global $wp_test_options;
			return $wp_test_options[ $option ] ?? $default;
		}
	}

	if ( ! function_exists( 'update_option' ) ) {
		function update_option( $option, $value ) {
			global $wp_test_options;
			$wp_test_options[ $option ] = $value;
			return true;
		}
	}

	if ( ! function_exists( 'add_option' ) ) {
		function add_option( $option, $value ) {
			global $wp_test_options;
			if ( ! isset( $wp_test_options[ $option ] ) ) {
				$wp_test_options[ $option ] = $value;
			}
			return true;
		}
	}

	if ( ! function_exists( 'wp_parse_args' ) ) {
		function wp_parse_args( $args, $defaults = array() ) {
			return array_merge( $defaults, $args );
		}
	}

	if ( ! function_exists( '__' ) ) {
		function __( $text, $domain = 'default' ) {
			return $text;
		}
	}

	if ( ! function_exists( 'esc_html__' ) ) {
		function esc_html__( $text, $domain = 'default' ) {
			return $text;
		}
	}
}
