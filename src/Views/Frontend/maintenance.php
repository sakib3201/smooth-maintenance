<?php
/**
 * Maintenance page template.
 *
 * Full-page maintenance mode view.
 *
 * @package SmoothMaintenance\Views\Frontend
 *
 * @var string $site_name The site name.
 * @var string $site_url  The site URL.
 * @var string $logo_url  The logo URL (optional).
 * @var string $message   The maintenance message.
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo esc_html( $site_name ); ?> &mdash; <?php echo esc_html__( 'Under Maintenance', 'smooth-maintenance' ); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php // phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php // phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>
	<style>
		*, *::before, *::after {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
			color: #ffffff;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			overflow: hidden;
			position: relative;
		}

		body::before {
			content: '';
			position: absolute;
			top: -50%;
			left: -50%;
			width: 200%;
			height: 200%;
			background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
						radial-gradient(circle at 70% 30%, rgba(168, 85, 247, 0.1) 0%, transparent 50%),
						radial-gradient(circle at 50% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
			animation: aurora 15s ease-in-out infinite alternate;
		}

		@keyframes aurora {
			0% { transform: rotate(0deg) scale(1); }
			50% { transform: rotate(3deg) scale(1.05); }
			100% { transform: rotate(-2deg) scale(1); }
		}

		.sm-maintenance-container {
			text-align: center;
			padding: 3rem 2rem;
			max-width: 600px;
			width: 100%;
			position: relative;
			z-index: 1;
		}

		.sm-maintenance-logo {
			margin-bottom: 2rem;
		}

		.sm-maintenance-logo img {
			max-width: 180px;
			height: auto;
			border-radius: 12px;
		}

		.sm-maintenance-icon {
			width: 80px;
			height: 80px;
			margin: 0 auto 2rem;
			background: rgba(255, 255, 255, 0.08);
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			border: 1px solid rgba(255, 255, 255, 0.12);
			border-radius: 24px;
			display: flex;
			align-items: center;
			justify-content: center;
			animation: float 3s ease-in-out infinite;
		}

		@keyframes float {
			0%, 100% { transform: translateY(0px); }
			50% { transform: translateY(-10px); }
		}

		.sm-maintenance-icon svg {
			width: 36px;
			height: 36px;
			stroke: #a78bfa;
		}

		.sm-maintenance-container h1 {
			font-size: 2.25rem;
			font-weight: 700;
			margin-bottom: 1rem;
			line-height: 1.2;
			background: linear-gradient(135deg, #ffffff 0%, #c4b5fd 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		.sm-maintenance-container p {
			font-size: 1.125rem;
			line-height: 1.7;
			color: rgba(255, 255, 255, 0.7);
			margin-bottom: 2rem;
			font-weight: 400;
		}

		.sm-maintenance-divider {
			width: 60px;
			height: 3px;
			background: linear-gradient(90deg, #6366f1, #a855f7);
			margin: 0 auto 2rem;
			border-radius: 3px;
		}

		.sm-maintenance-site-name {
			font-size: 0.875rem;
			color: rgba(255, 255, 255, 0.4);
			font-weight: 500;
			letter-spacing: 0.05em;
			text-transform: uppercase;
		}

		.sm-maintenance-site-name a {
			color: rgba(255, 255, 255, 0.5);
			text-decoration: none;
			transition: color 0.3s ease;
		}

		.sm-maintenance-site-name a:hover {
			color: rgba(255, 255, 255, 0.8);
		}

		/* Responsive */
		@media (max-width: 640px) {
			.sm-maintenance-container {
				padding: 2rem 1.5rem;
			}

			.sm-maintenance-container h1 {
				font-size: 1.75rem;
			}

			.sm-maintenance-container p {
				font-size: 1rem;
			}

			.sm-maintenance-icon {
				width: 64px;
				height: 64px;
				border-radius: 18px;
			}

			.sm-maintenance-icon svg {
				width: 28px;
				height: 28px;
			}
		}
	</style>
</head>
<body>
	<div class="sm-maintenance-container" role="main">
		<?php if ( ! empty( $logo_url ) ) : ?>
			<div class="sm-maintenance-logo">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
			</div>
		<?php else : ?>
			<div class="sm-maintenance-icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.194-.14 1.743" />
				</svg>
			</div>
		<?php endif; ?>

		<h1><?php echo esc_html__( "We'll Be Back Soon!", 'smooth-maintenance' ); ?></h1>

		<div class="sm-maintenance-divider"></div>

		<p><?php echo esc_html( $message ); ?></p>

		<div class="sm-maintenance-site-name">
			<a href="<?php echo esc_url( $site_url ); ?>"><?php echo esc_html( $site_name ); ?></a>
		</div>
	</div>
</body>
</html>
