<?php
/**
 * Plugin Activator.
 *
 * Runs on plugin activation.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

defined( 'ABSPATH' ) || exit;

class Activator {

	/**
	 * Run activation tasks.
	 *
	 * @return void
	 */
	public static function activate(): void {
		// Create custom database tables.
		Database::createTables();

		// Set default settings.
		self::setDefaults();

		// Store plugin version.
		update_option( 'smooth_maintenance_version', Constants::VERSION );

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Set default plugin settings.
	 *
	 * @return void
	 */
	protected static function setDefaults(): void {
		$defaults = array(
			'maintenance_mode_enabled' => false,
			'active_template'          => 0,
			'version'                  => Constants::VERSION,
		);

		$options = get_option( Constants::OPTION_NAME );

		if ( ! $options ) {
			add_option( Constants::OPTION_NAME, $defaults );
			$options = $defaults;
		}

		// Insert default Gutenberg template if one hasn't been set.
		if ( empty( $options['active_template'] ) ) {
			$template_id = self::insertDefaultTemplate();
			if ( $template_id ) {
				$options['active_template'] = $template_id;
				update_option( Constants::OPTION_NAME, $options );
			}
		}
	}

	/**
	 * Insert the default Gutenberg template.
	 *
	 * @return int|false Post ID on success, false on failure.
	 */
	protected static function insertDefaultTemplate(): int|false {
		// Define the default Gutenberg block markup.
		$content = '
<!-- wp:cover {"url":"","dimRatio":90,"overlayColor":"black","isDark":false,"align":"full","style":{"color":{"background":"#0f172a"}}} -->
<div class="wp-block-cover alignfull is-light" style="background-color:#0f172a"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-90 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","wideSize":"800px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"4rem","fontWeight":"800"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:4rem;font-weight:800">Something Beautiful Is Coming</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.25rem"}},"textColor":"contrast-3"} -->
<p class="has-text-align-center has-contrast-3-color has-text-color" style="font-size:1.25rem">We are meticulously crafting the new experience. Hang tight, we will be launching shortly.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"2rem"} -->
<div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:smooth-maintenance/countdown {"endDate":"' . gmdate( 'Y-m-d\TH:i:sP', strtotime( '+7 days' ) ) . '","expiredMessage":"We are live! Refresh the page."} /-->
</div>
<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
		';

		$post_data = array(
			'post_title'   => 'Default Countdown Template',
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => PostTypes::TEMPLATE_CPT,
			'post_author'  => 1,
		);

		$post_id = wp_insert_post( $post_data );

		return is_wp_error( $post_id ) ? false : $post_id;
	}
}
