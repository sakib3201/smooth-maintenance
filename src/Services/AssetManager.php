<?php
/**
 * Asset Manager Service.
 *
 * Handles performance optimizations, caching, and asset cleanups
 * specifically for the Gutenberg-native frontend canvas.
 *
 * @package SmoothMaintenance\Services
 */

namespace SmoothMaintenance\Services;

defined( 'ABSPATH' ) || exit;

class AssetManager {

	/**
	 * Hook in all frontend optimizations.
	 *
	 * @return void
	 */
	public function init(): void {
		// Only optimize if we are rendering the maintenance canvas.
		add_action( 'wp_enqueue_scripts', array( $this, 'strip_core_bloat' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_optimized_assets' ), 10 );
		add_filter( 'script_loader_tag', array( $this, 'defer_custom_scripts' ), 10, 2 );
		add_action( 'wp_head', array( $this, 'inline_critical_styles' ), 1 );
	}

	/**
	 * Inline critical block styles to avoid FOUC.
	 *
	 * @return void
	 */
	public function inline_critical_styles(): void {
		// We can get the styles of specific core blocks OR our own blocks.
		// For simplicity/performance, we'll try to find the handles for enqueued block library styles.
		// In a real high-perf scenario, we'd extract specific block CSS.
		// For now, let's print all enqueued block styles as inline to avoid extra requests on mobile.
		global $wp_styles;

		$block_library_handles = array( 'wp-block-library', 'smooth-maintenance-blocks-style' );
		
		foreach ( $block_library_handles as $handle ) {
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}
			
			$style_obj = $wp_styles->registered[ $handle ];
			if ( ! empty( $style_obj->src ) ) {
				$path = ABSPATH . str_replace( site_url(), '', $style_obj->src );
				if ( file_exists( $path ) ) {
					echo '<style id="' . esc_attr( $handle ) . '-inline-css">' . file_get_contents( $path ) . '</style>'; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.Security.EscapeOutput.OutputNotEscaped
					wp_dequeue_style( $handle );
				}
			}
		}
	}

	/**
	 * Strip unnecessary WordPress core bloat from the maintenance canvas.
	 * We want absolute maximum performance here.
	 *
	 * @return void
	 */
	public function strip_core_bloat(): void {
		// Remove Emojis
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		
		// Remove Embeds
		wp_deregister_script( 'wp-embed' );
		
		// Remove Dashicons (unless user logged in, which they shouldn't be here)
		wp_deregister_style( 'dashicons' );
		
		// Remove unnecessary generator meta tags for security & tiny size savings
		remove_action( 'wp_head', 'wp_generator' );

        // Remove WP block library theme CSS (we only need the core block styles, not theme overrides)
        wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * Enqueue our custom frontend assets.
	 *
	 * @return void
	 */
	public function enqueue_optimized_assets(): void {
		// Any specific frontend assets we might need globally could go here.
        // Currently, blocks enqueue their own assets via block.json which is highly performant.
	}

	/**
	 * Add 'defer' attribute to our custom block scripts to ensure they don't block rendering.
	 *
	 * @param string $tag    The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @return string
	 */
	public function defer_custom_scripts( string $tag, string $handle ): string {
		// Add defer to our specific vanilla JS block scripts
		$deferred_scripts = array(
			'smooth-maintenance-countdown-view-script',
			'smooth-maintenance-subscriber-form-view-script',
		);

		if ( in_array( $handle, $deferred_scripts, true ) ) {
			if ( false === strpos( $tag, 'defer' ) ) {
				return str_replace( ' src', ' defer="defer" src', $tag );
			}
		}

		return $tag;
	}
}
