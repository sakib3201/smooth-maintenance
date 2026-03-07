=== Smooth Maintenance ===
Contributors: sakib3201
Tags: maintenance mode, coming soon, under construction, maintenance, countdown
Requires at least: 6.2
Tested up to: 6.7
Stable tag: 1.0.0
Requires PHP: 8.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Put your site in maintenance mode in one step super fast and smooth.
Fully customizable super lightweight Gutenberg templates.

== Description ==

Smooth Maintenance lets you activate a maintenance or coming soon page in one click, without touching a single line of code. Unlike other maintenance plugins, it uses the native WordPress block editor (Gutenberg) for page design — no page builders, no shortcodes, no bloat.

**Key Features**

* **Instant toggle** — Enable or disable maintenance mode from the admin dashboard. Logged-in administrators always bypass the maintenance page automatically.
* **Block editor templates** — Design your maintenance page using any Gutenberg block. Three polished starter templates are included and ready to use on activation.
* **Countdown Timer block** — Set a target date and display a live countdown to build anticipation before your launch.
* **Subscriber Form block** — Capture email addresses directly from your maintenance page. Collected subscribers are stored in your WordPress database and manageable from the admin.
* **Subscriber management** — View, export, and manage your email subscriber list from a dedicated dashboard inside WordPress.
* **Live preview** — Preview your maintenance page from the WordPress editor before going live, with no impact on site visitors.
* **Proper HTTP headers** — Serves a `503 Service Unavailable` status with a `Retry-After` header so search engines handle your downtime correctly.
* **React-powered admin UI** — A modern, fast settings panel built with React for a smooth admin experience.
* **Developer-friendly** — Extend or override behaviour using action and filter hooks:
  * `smooth_maintenance_bypass` — Programmatically bypass maintenance mode for specific users or conditions.
  * `smooth_maintenance_html` — Filter the full HTML output of the maintenance page.

**Why Smooth Maintenance?**

Most maintenance plugins force you to use a separate page builder or a locked-down template editor. Smooth Maintenance treats your maintenance page as a first-class WordPress content type, so you design it with the same familiar block editor you already use — and keep full control over the result.

== Installation ==

1. Upload the `smooth-maintenance` folder to the `/wp-content/plugins/` directory, or install it directly from the WordPress plugin directory via **Plugins > Add New**.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Navigate to **Smooth Maintenance** in the admin sidebar.
4. Choose a starter template or design your own, then toggle maintenance mode on.

That's it. Administrators will continue to see your live site while visitors see the maintenance page.

== Frequently Asked Questions ==

= Will my site be visible to admins while maintenance mode is active? =

Yes. Logged-in administrators always bypass the maintenance page and see the live site. You can extend bypass logic for other roles or conditions using the `smooth_maintenance_bypass` filter.

= Will search engines penalise my site during maintenance? =

No. Smooth Maintenance serves a proper `503 Service Unavailable` HTTP status along with a `Retry-After` header, signalling to search engine crawlers that the downtime is temporary and they should come back later.

= Can I use my own blocks and plugins inside the maintenance page? =

Yes. The maintenance page is edited in the standard WordPress block editor, so any block from any plugin works exactly as it does on a normal post or page.

= Where are subscriber emails stored? =

Emails collected via the Subscriber Form block are stored in your WordPress database. You can view and manage them from the Subscriber management screen inside the plugin dashboard.

= Can I export my subscriber list? =

Yes. The subscriber management dashboard includes an export option so you can download your list at any time.

= Is this plugin compatible with caching plugins? =

Yes, but you should exclude the maintenance page URL from your caching plugin's cache to ensure visitors always see the correct page. Refer to your caching plugin's documentation for instructions on excluding URLs.

= How do I preview the maintenance page without enabling maintenance mode? =

Use the **Preview** button available inside the block editor template screen. This opens a preview of the maintenance page visible only to you, without affecting site visitors.

== Screenshots ==

1. The Smooth Maintenance admin dashboard with maintenance mode toggle.
2. Designing a maintenance page in the WordPress block editor.
3. The Countdown Timer block in action.
4. The Subscriber Form block collecting emails.
5. Subscriber management dashboard.

== Changelog ==

= 1.0.0 =
* Initial release.
* Maintenance mode toggle with administrator bypass.
* Three built-in starter templates activated on install.
* Countdown Timer block with configurable target date.
* Subscriber Form block with in-database email storage.
* Subscriber management dashboard with export.
* Live preview of maintenance pages from the block editor.
* React-powered admin settings UI.
* 503 status and Retry-After header support.
* Developer hooks: `smooth_maintenance_bypass`, `smooth_maintenance_html`.

== Upgrade Notice ==

= 1.0.0 =
Initial release. No upgrade steps required.
