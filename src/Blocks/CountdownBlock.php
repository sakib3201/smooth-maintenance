<?php
/**
 * Register Gutenberg Blocks.
 *
 * @package SmoothMaintenance\Blocks
 */

namespace SmoothMaintenance\Blocks;

use SmoothMaintenance\Core\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * CountdownBlock class.
 */
class CountdownBlock {

	/**
	 * Register the block type.
	 *
	 * @return void
	 */
	public function register(): void {
		// Read block metadata using the built-in WordPress function.
		// The path points to the 'build' directory where @wordpress/scripts outputs it.
		$block_dir = Constants::pluginPath() . 'build/blocks/countdown';

		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type( $block_dir );
		}
	}
}
