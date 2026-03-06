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

		// Insert 3 premium templates if none exist.
		if ( empty( $options['active_template'] ) ) {
			$template_ids = self::insertDefaultTemplates();
			if ( ! empty( $template_ids ) ) {
				$options['active_template'] = $template_ids[0]; // Set the first one (Countdown) as default.
				update_option( Constants::OPTION_NAME, $options );
			}
		}
	}

	/**
	 * Insert the default Gutenberg templates.
	 *
	 * @return array List of inserted post IDs.
	 */
	protected static function insertDefaultTemplates(): array {
		$template_ids = array();

		// 1. Premium Countdown Template (High Impact)
		$countdown_content = '
<!-- wp:cover {"url":"","dimRatio":90,"overlayColor":"black","isDark":false,"align":"full","style":{"color":{"background":"#0f172a"}}} -->
<div class="wp-block-cover alignfull is-light" style="background-color:#0f172a"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-90 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","wideSize":"800px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"4rem","fontWeight":"900"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:4rem;font-weight:900">Something Beautiful Is Coming</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.25rem"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.25rem;opacity:0.8">We are meticulously crafting the new experience. Hang tight, we will be launching shortly.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"3rem"} -->
<div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:smooth-maintenance/countdown {"endDate":"' . gmdate( 'Y-m-d\TH:i:sP', strtotime( '+7 days' ) ) . '","expiredMessage":"We are live! Refresh the page."} /-->
</div>
<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
		';

		// 2. Minimalist Maintenance (Clean, Logo Focused)
		$minimal_content = '
<!-- wp:cover {"url":"","dimRatio":100,"overlayColor":"black","isDark":false,"align":"full","style":{"color":{"background":"#0f172a"}}} -->
<div class="wp-block-cover alignfull is-light" style="background-color:#0f172a"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","wideSize":"600px"}} -->
<div class="wp-block-group">
<!-- wp:image {"align":"center","width":120,"height":120} -->
<figure class="wp-block-image aligncenter is-resized"><img src="' . Constants::pluginUrl() . 'assets/admin/src/assets/logo.svg" alt="Maintenance Logo" width="120" height="120"/></figure>
<!-- /wp:image -->
<!-- wp:spacer {"height":"2rem"} -->
<div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"800"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:2.5rem;font-weight:800">Under Maintenance</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.1rem"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.1rem;opacity:0.7">We are currently performing scheduled maintenance to improve our service. Check back with us very soon.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
		';

		// 3. Subscriber Focused (Coming Soon with Form)
		$subscriber_content = '
<!-- wp:cover {"url":"","dimRatio":95,"overlayColor":"black","isDark":false,"align":"full","style":{"color":{"background":"#000000"}}} -->
<div class="wp-block-cover alignfull is-light" style="background-color:#000000"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-95 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:group {"layout":{"type":"constrained","wideSize":"700px"}} -->
<div class="wp-block-group">
<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"3rem","fontWeight":"900"}},"textColor":"white"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="font-size:3rem;font-weight:900">Be The First To Know</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.25rem"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.25rem;opacity:0.8">Subscribe to our newsletter and we will notify you exactly when we open our doors to the public.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"3rem"} -->
<div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:smooth-maintenance/subscriber-form {"placeholder":"Type your best email...","buttonText":"Notify Me"} /-->
</div>
<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
		';

		$templates = array(
			'Premium Countdown'         => $countdown_content,
			'Minimal Maintenance'       => $minimal_content,
			'Subscriber Coming Soon'    => $subscriber_content,
		);

		foreach ( $templates as $title => $content ) {
			$post_id = wp_insert_post( array(
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => PostTypes::TEMPLATE_CPT,
				'post_author'  => 1,
			) );

			if ( ! is_wp_error( $post_id ) ) {
				$template_ids[] = $post_id;
			}
		}

		return $template_ids;
	}
}
