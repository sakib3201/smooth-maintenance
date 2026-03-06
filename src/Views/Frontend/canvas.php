<?php
/**
 * Frontend Canvas Template.
 *
 * Provides a clean HTML shell for rendering Gutenberg blocks in full screen
 * without relying on the active theme's headers, footers, or wrappers.
 *
 * @package SmoothMaintenance\Views
 *
 * @var string $title        The site title.
 * @var string $html_content The fully rendered block HTML.
 */

defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( wp_strip_all_tags( $title ) ); ?></title>
	<?php
	// Enqueue core block styles and our custom block styles.
	wp_head();
	?>
	<style>
		/* Ensure a true full-screen canvas without theme margin/padding */
		html, body {
			margin: 0;
			padding: 0;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			background-color: #0f172a; /* Fallback */
		}
		/* Ensure blocks like Cover stretch fully */
		body > .wp-block-cover,
		body > .wp-block-group {
			flex-grow: 1;
			min-height: 100vh;
		}
		/* Hide admin bar on frontend for cleaner look */
		#wpadminbar {
			display: none !important;
		}
		html {
			margin-top: 0 !important;
		}
	</style>
</head>
<body <?php body_class( 'smooth-maintenance-canvas' ); ?>>

	<?php
	// Output the pre-rendered Gutenberg block content.
	// We do not escape this because it is generated via do_blocks()
	// and needs to render HTML.
	echo $html_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php
	// Include necessary footer scripts (like our countdown view.js)
	wp_footer();
	?>
</body>
</html>
