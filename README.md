# Smooth Maintenance

The smoothest maintenance mode plugin for WordPress. Built with modern MVC architecture, React admin interface, and developer-friendly extensibility.

## Features

- **One-Click Toggle** — Enable/disable maintenance mode via a clean React admin UI
- **Admin Bypass** — Logged-in admins automatically see the live site
- **REST API** — Full REST API for settings management (`smooth-maintenance/v1`)
- **Modern Architecture** — MVC pattern with service container, PSR-4 autoloading
- **Theme Override** — Drop `smooth-maintenance/maintenance.php` in your theme to customize
- **Extensible** — Filters for bypass logic, template paths, and content
- **503 Status** — Proper `503 Service Unavailable` with `Retry-After` header

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Node.js 20+ (for development)

## Setup & Installation

### Quick Install (Production)

1. Download or clone the plugin into `wp-content/plugins/smooth-maintenance/`
2. Run dependency installs:
   ```bash
   cd wp-content/plugins/smooth-maintenance
   composer install --no-dev
   npm install
   npm run build
   ```
3. Activate the plugin in **WordPress Admin → Plugins**
4. Go to **Maintenance** in the admin sidebar to toggle maintenance mode

### Development Setup

1. Clone the repository:
   ```bash
   cd wp-content/plugins/
   git clone <repo-url> smooth-maintenance
   cd smooth-maintenance
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node dependencies:
   ```bash
   npm install
   ```

4. Start the dev server (hot reload):
   ```bash
   npm run start
   ```

5. Build production assets:
   ```bash
   npm run build
   ```

6. Activate the plugin in WordPress admin

### Production Build (WordPress.org Release)

One command creates a clean distribution folder and versioned zip in `plugins/dist/`:

```bash
npm run production-build
```

**What it does:**
- Runs `npm run build` (minified JS/CSS)
- Installs Composer deps with `--no-dev --optimize-autoloader`
- Copies only distributable files to `plugins/dist/smooth-maintenance/`
- Creates `plugins/dist/smooth-maintenance-x.x.x.zip`

**Excluded from the zip:** `node_modules/`, `vendor/` (dev), `tests/`, `assets/` (src), `.github/`, dev config files.

The zip file is named with the version from the plugin header (e.g. `smooth-maintenance-1.0.0.zip`) and is ready for WordPress.org submission.


### Running Tests

```bash
# PHP unit tests
composer test

# JavaScript tests
npm test

# PHP CodeSniffer (WordPress standards)
composer phpcs

# JavaScript linting
npm run lint:js
```

## Architecture

```
smooth-maintenance/
├── smooth-maintenance.php     # Plugin entry point
├── bootstrap.php              # Singleton app container, DI registration
├── autoloader.php             # PSR-4 autoloader
├── src/
│   ├── Core/                  # Framework: Container, Router, Loader, BaseModel, BaseController
│   ├── Models/                # Settings (wp_options), Subscriber (custom table)
│   ├── Controllers/           # Admin/ (menu, assets) and Api/ (REST endpoints)
│   ├── Services/              # MaintenanceService (bypass logic, rendering)
│   ├── Middleware/             # AuthMiddleware (capability checks)
│   └── Views/                 # Admin/ (React mount) and Frontend/ (maintenance page)
├── assets/admin/src/          # React app: store (@wordpress/data), components
├── tests/                     # PHPUnit + Vitest
└── .github/workflows/         # CI/CD pipeline
```

## REST API

| Endpoint | Method | Permission | Description |
|----------|--------|------------|-------------|
| `/wp-json/smooth-maintenance/v1/settings` | GET | `manage_options` | Get settings |
| `/wp-json/smooth-maintenance/v1/settings` | POST | `manage_options` | Update settings |

## Adding New Templates

Smooth Maintenance v1.1 is fully **Gutenberg-native**. We do not use proprietary drag-and-drop builders. Instead, your templates are designed using the core WordPress Block Editor.

To add a new template:
1. Go to **Maintenance → Templates** in your WordPress admin.
2. Click **Add New**.
3. Use native Gutenberg blocks (Cover, Group, Heading, etc.) to design your maintenance landing page.
4. Insert the **Countdown Timer** block (`smooth-maintenance/countdown`) if you want a live counter.
5. Publish the template.
6. Go back to **Maintenance → Settings** and select your new template from the dropdown.

The plugin will automatically render your template on the frontend *without* your active theme's header or footer, ensuring a true full-screen landing page experience while preserving all block styles.

## Extensibility

```php
// Custom bypass logic (e.g., by role or IP)
add_filter( 'smooth_maintenance_bypass', function( $bypass, $user ) {
    if ( user_can( $user, 'editor' ) ) return true;
    return $bypass;
}, 10, 2 );

// Filter the fully rendered HTML output of the block template
add_filter( 'smooth_maintenance_html', function( $html ) {
    // You can inject custom scripts or pixels here
    return $html;
} );
```

## Roadmap

- **v1.2** — Email subscriber capture block, Mailchimp/ConvertKit webhooks
- **v1.3** — Preview link with secret tokens, IP whitelist, role-based bypass
- **v2.0** — Advanced Analytics, A/B testing of templates

## License

GPL-2.0-or-later
