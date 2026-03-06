<?php
/**
 * Subscriber Form Block.
 *
 * @package SmoothMaintenance\Blocks
 */

namespace SmoothMaintenance\Blocks;

use SmoothMaintenance\Core\Constants;

defined( 'ABSPATH' ) || exit;

/**
 * SubscriberFormBlock class.
 */
class SubscriberFormBlock {

	/**
	 * Register the block type.
	 *
	 * @return void
	 */
	public function register(): void {
		$block_dir = Constants::pluginPath() . 'build/blocks/subscriber-form';

		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type( $block_dir );
		}
	}
}
