# Smooth Maintenance Plugin - Implementation Plan v1.0

## Overview

Build a WordPress maintenance mode plugin with modern architecture: service container backend (PHP 8+), React admin interface using @wordpress/scripts and @wordpress/data, complete testing infrastructure (PHPUnit + Vitest + GitHub Actions), and hybrid database approach (wp_options for settings, custom table for subscribers).

**First Iteration Goal:** Working prototype with toggle functionality - admin can enable/disable maintenance mode via React UI, non-admins see maintenance page, admins bypass automatically.

---

## Architecture Summary

### Tech Stack
- **Backend:** PHP 8+, PSR-4 autoloading (Composer), custom lightweight service container
- **Frontend:** React, @wordpress/scripts (Webpack), @wordpress/data (state management)
- **Testing:** PHPUnit (backend), Vitest (frontend), GitHub Actions CI/CD
- **Database:** Hybrid - wp_options for settings, custom table for subscribers

### Key Patterns: **MVC Architecture (Laravel-inspired, WordPress-adapted)**
- **Models:** Data access and business logic (like Eloquent but lightweight)
- **Views:** React components (admin UI) + PHP templates (maintenance page)
- **Controllers:** REST API controllers + Admin page controllers
- **Service Container:** Dependency injection for all components
- **Router:** Clean REST API route registration
- **Base Classes:** Abstract Model and Controller classes for DRY principles
- **WordPress Integration:** Centralized hooks registration via Loader class

---

## Directory Structure

```
smooth-maintenance/
├── smooth-maintenance.php          # Main plugin file (header, bootstrap)
├── autoloader.php                  # PSR-4 autoloader
├── bootstrap.php                   # Bootstrap class (singleton, container init)
├── composer.json                   # PHP dependencies & autoloading
├── package.json                    # Node dependencies (@wordpress/*)
├── phpunit.xml                     # PHPUnit configuration
├── .phpcs.xml.dist                 # WordPress coding standards
├── .gitignore                      # Ignore: node_modules, vendor, build, docs/
│
├── src/                            # PHP source (PSR-4: SmoothMaintenance\)
│   ├── Models/                     # MVC: Models (data + business logic)
│   │   ├── Settings.php            # Settings model (extends BaseModel)
│   │   └── Subscriber.php          # Subscriber model (extends BaseModel)
│   │
│   ├── Controllers/                # MVC: Controllers (request handling)
│   │   ├── Api/
│   │   │   └── MaintenanceController.php  # REST API controller
│   │   └── Admin/
│   │       └── AdminController.php # Admin page controller
│   │
│   ├── Views/                      # MVC: Views (presentation)
│   │   ├── Admin/
│   │   │   └── index.php           # Admin page view (React mount point)
│   │   └── Frontend/
│   │       └── maintenance.php     # Maintenance page template
│   │
│   ├── Services/                   # Service layer (complex business logic)
│   │   └── MaintenanceService.php  # Maintenance mode service
│   │
│   ├── Core/                       # Framework core classes
│   │   ├── BaseModel.php           # Abstract model (like Eloquent)
│   │   ├── BaseController.php      # Abstract controller
│   │   ├── Router.php              # REST API router
│   │   ├── Container.php           # Service container (DI)
│   │   ├── Loader.php              # WordPress hooks manager
│   │   ├── Database.php            # Database migrations
│   │   ├── Activator.php           # Plugin activation
│   │   ├── Deactivator.php         # Plugin deactivation
│   │   └── Constants.php           # Plugin constants
│   │
│   └── Middleware/                 # Request middleware (WordPress hooks)
│       └── AuthMiddleware.php      # Capability checks
│
├── assets/admin/src/               # React admin app
│   ├── index.js                    # Entry point
│   ├── App.js                      # Main component
│   ├── components/
│   │   ├── Header.js
│   │   └── MaintenanceToggle.js    # Toggle switch (connected to store)
│   ├── store/
│   │   ├── index.js                # Register @wordpress/data store
│   │   ├── actions.js              # updateSettings()
│   │   ├── selectors.js            # isMaintenanceEnabled()
│   │   ├── reducer.js              # State management
│   │   └── resolvers.js            # Auto-fetch from REST API
│   └── styles/
│       └── admin.scss
│
# Templates moved to src/Views/ (MVC pattern)
│
├── tests/
│   ├── php/                        # PHPUnit tests
│   │   ├── bootstrap.php
│   │   ├── Unit/
│   │   │   ├── ContainerTest.php
│   │   │   ├── SettingsRepositoryTest.php
│   │   │   └── MaintenanceModeTest.php
│   │   └── Integration/
│   │       └── ApiTest.php
│   └── js/                         # Vitest tests
│       ├── vitest.config.js
│       └── store/
│           ├── actions.test.js
│           └── selectors.test.js
│
├── .github/workflows/
│   └── ci.yml                      # GitHub Actions (PHPCS, PHPUnit, Vitest)
│
└── docs/                           # Gitignored markdown plans
    └── architecture.md
```

---

## Critical Files & Their Responsibilities (MVC Architecture)

### Bootstrap & Core Framework

**smooth-maintenance.php** `C:\Users\Win10\Local Sites\wpt\app\public\wp-content\plugins\smooth-maintenance\smooth-maintenance.php`
- WordPress plugin header (name, version, requirements)
- PHP 8+ version check
- Load autoloader.php
- Initialize Bootstrap singleton
- Register activation/deactivation hooks

**bootstrap.php** `C:\Users\Win10\Local Sites\wpt\app\public\wp-content\plugins\smooth-maintenance\bootstrap.php`
- Singleton pattern (application container)
- Define constants (VERSION, PLUGIN_PATH, PLUGIN_URL)
- Initialize service container (DI)
- Register all services (models, controllers, services)
- Initialize router and hooks loader
- Boot the application

**src/Core/Container.php**
- Service container (dependency injection)
- `bind(abstract, concrete)` - Register binding
- `singleton(abstract, concrete)` - Register singleton
- `make(abstract)` - Resolve service with dependencies
- Laravel-inspired API but lightweight

**src/Core/Router.php**
- REST API route registration
- `get(route, controller, method)` - Register GET route
- `post(route, controller, method)` - Register POST route
- Route groups with middleware support
- Clean route definitions (Laravel-style)

**src/Core/Loader.php**
- WordPress hooks manager
- `addAction(hook, callback, priority, args)` - Register action
- `addFilter(hook, callback, priority, args)` - Register filter
- `run()` - Execute all registered hooks
- Centralized hook management

### MVC Layer: Models

**src/Core/BaseModel.php**
- Abstract base model (like Eloquent but lightweight)
- Query builder methods: `find()`, `where()`, `get()`, `save()`, `delete()`
- Magic properties for attributes
- Timestamps support (created_at, updated_at)
- Validation hooks
- Child models extend this

**src/Models/Settings.php** (extends BaseModel)
- Settings model (wraps wp_options)
- Properties: `maintenance_mode_enabled`, `version`
- Methods:
  - `static get(key, default)` - Get setting value
  - `static set(key, value)` - Set setting value
  - `static all()` - Get all settings
  - `isMaintenanceEnabled()` - Check if enabled
- Validation rules for settings
- Default values management

**src/Models/Subscriber.php** (extends BaseModel)
- Subscriber model (custom table: sm_subscribers)
- Properties: `id`, `email`, `subscribed_at`, `ip_address`, `user_agent`
- Methods:
  - `static findByEmail(email)` - Find subscriber by email
  - `static create(data)` - Create new subscriber
  - `validate()` - Validate email format
- Validation rules
- Not used in iteration 1, but foundation ready

### MVC Layer: Controllers

**src/Core/BaseController.php**
- Abstract base controller
- Common methods:
  - `success(data, message, code)` - Success response
  - `error(message, code)` - Error response
  - `validate(request, rules)` - Validate request data
  - `authorize(capability)` - Check user permissions
- WP_REST_Response helpers
- Nonce validation

**src/Controllers/Api/MaintenanceController.php** (extends BaseController)
- REST API controller for maintenance mode
- Namespace: `smooth-maintenance/v1`
- Methods:
  - `index(WP_REST_Request)` - GET /settings - Retrieve settings
  - `update(WP_REST_Request)` - POST /settings - Update settings
  - `permissions()` - Check manage_options capability
- Uses Settings model for data
- Returns JSON responses

**src/Controllers/Admin/AdminController.php** (extends BaseController)
- Admin page controller
- Methods:
  - `index()` - Render admin page (loads view)
  - `enqueueAssets()` - Enqueue admin JS/CSS
  - `localizeScript()` - Pass data to React app
- Loads Views/Admin/index.php
- Handles admin menu registration

### MVC Layer: Views

**src/Views/Admin/index.php**
- Admin page view (presentation layer)
- Renders div with id `smooth-maintenance-admin`
- React mount point
- Clean HTML, no business logic
- Receives data from AdminController

**src/Views/Frontend/maintenance.php**
- Maintenance page template (MVC View)
- Full-height centered layout
- Variables: `$site_name`, `$site_url`, `$logo_url`, `$message`
- Responsive design, inline CSS
- Theme override support: `{theme}/smooth-maintenance/maintenance.php`

### Service Layer (Business Logic)

**src/Services/MaintenanceService.php**
- Complex business logic for maintenance mode
- Methods:
  - `shouldShowMaintenance()` - Determine if maintenance page should show
  - `canBypass(user)` - Check if user can bypass
  - `render()` - Render maintenance page (loads view)
  - `setStatus()` - Set HTTP 503 status and headers
- Uses Settings model
- Called by template_redirect hook
- Separation of concerns: controller → service → model

### Middleware

**src/Middleware/AuthMiddleware.php**
- Capability-based authorization
- Methods:
  - `handle(request, capability)` - Check if user has capability
  - `unauthorized()` - Return 401 response
- Used by REST API routes
- Laravel-inspired middleware pattern

### React Admin App

**assets/admin/src/index.js**
- Import App, store
- Register store: `@wordpress/data.registerStore('smooth-maintenance/settings')`
- Render App into `#smooth-maintenance-admin`

**assets/admin/src/App.js**
- Layout container: Header + MaintenanceToggle
- Uses `@wordpress/components` (Card, CardBody)

**assets/admin/src/components/MaintenanceToggle.js**
- Connected component using `useSelect` and `useDispatch`
- `useSelect`: Gets `isMaintenanceEnabled`, `isSaving` from store
- `useDispatch`: Calls `updateSettings({maintenance_mode_enabled: value})`
- Renders `@wordpress/components` ToggleControl
- Shows loading state, success/error notices

**assets/admin/src/store/index.js**
- Exports: actions, selectors, reducer, resolvers
- Registers with `@wordpress/data`

**assets/admin/src/store/actions.js**
- `setSettings(settings)` - Update store state
- `setSaving(isSaving)` - Update saving flag
- `updateSettings(settings)` - Async generator: POST to REST API

**assets/admin/src/store/resolvers.js**
- `getSettings()` - Auto-fetch from GET /settings on first selector call

---

## Database Schema

### Custom Table: {prefix}sm_subscribers

```sql
CREATE TABLE {prefix}sm_subscribers (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    email varchar(255) NOT NULL,
    subscribed_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_address varchar(45) DEFAULT NULL,
    user_agent text DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY email (email),
    KEY subscribed_at (subscribed_at)
);
```

### WordPress Option: smooth_maintenance_settings

```json
{
    "maintenance_mode_enabled": false,
    "version": "1.0.0"
}
```

---

## REST API Specification

### GET /wp-json/smooth-maintenance/v1/settings

**Permission:** `manage_options`

**Response (200):**
```json
{
    "maintenance_mode_enabled": false,
    "version": "1.0.0"
}
```

### POST /wp-json/smooth-maintenance/v1/settings

**Permission:** `manage_options`

**Body:**
```json
{
    "maintenance_mode_enabled": true
}
```

**Validation:** `maintenance_mode_enabled` must be boolean

**Response (200):**
```json
{
    "success": true,
    "data": {
        "maintenance_mode_enabled": true,
        "version": "1.0.0"
    }
}
```

---

## Implementation Order (70 Steps - MVC Architecture)

### Phase 1: Foundation & Core Framework

1. Create directory structure (src/Models, src/Controllers, src/Views, src/Services, src/Core, src/Middleware, tests/, assets/, docs/, .github/workflows/)
2. Create .gitignore (add: node_modules/, vendor/, build/, docs/)
3. Create composer.json (PHP 8+, PSR-4 autoload SmoothMaintenance\\)
4. Run `composer install`
5. Create smooth-maintenance.php (plugin header, version check, load autoloader)
6. Create autoloader.php (PSR-4 autoloader for SmoothMaintenance namespace)

**Core Framework Classes:**

7. Create src/Core/Container.php (DI container: bind(), singleton(), make())
8. Create src/Core/Constants.php (VERSION, PLUGIN_PATH, PLUGIN_URL)
9. Create src/Core/Loader.php (WordPress hooks manager: addAction(), addFilter(), run())
10. Create src/Core/Router.php (REST API router: get(), post(), group())
11. Create src/Core/Database.php (migrations: createTables(), schema definitions)
12. Create src/Core/Activator.php (plugin activation: run migrations, set defaults)
13. Create src/Core/Deactivator.php (plugin deactivation: cleanup)

**Base MVC Classes:**

14. Create src/Core/BaseModel.php (abstract model with query builder: find(), where(), save(), delete())
15. Create src/Core/BaseController.php (abstract controller: success(), error(), validate(), authorize())

**Bootstrap:**

16. Create bootstrap.php (Bootstrap singleton: init container, register services, boot application)
17. Test: Activate plugin in WordPress, verify no errors

### Phase 2: MVC - Models (Data Layer)

18. Create src/Models/Settings.php (extends BaseModel)
    - Properties: maintenance_mode_enabled, version
    - Methods: static get(), static set(), static all(), isMaintenanceEnabled()
    - Validation rules
    - Default values

19. Create src/Models/Subscriber.php (extends BaseModel)
    - Properties: id, email, subscribed_at, ip_address, user_agent
    - Methods: static findByEmail(), static create(), validate()
    - Table: sm_subscribers
    - Not used in iteration 1, but foundation ready

20. Update Database.php with Subscriber table schema

21. Update Bootstrap to register models in container as singletons

22. Test: Create unit tests for Settings model (get/set operations)

### Phase 3: MVC - Controllers (Request Handlers)

**Base Controller:**

23. Complete src/Core/BaseController.php implementation
    - success() - WP_REST_Response with 200
    - error() - WP_REST_Response with error code
    - validate() - Validate request data against rules
    - authorize() - Check user capabilities

**API Controller:**

24. Create src/Controllers/Api/MaintenanceController.php (extends BaseController)
    - index() method - GET /settings - Return Settings::all()
    - update() method - POST /settings - Validate and save
    - permissions() - Check manage_options
    - Inject Settings model via constructor

**Admin Controller:**

25. Create src/Controllers/Admin/AdminController.php (extends BaseController)
    - index() method - Load view (Views/Admin/index.php)
    - enqueueAssets() - Enqueue build/index.js, localize script
    - registerMenu() - Add top-level WordPress menu

26. Update Bootstrap to register controllers in container

27. Test: Unit tests for controller methods (mock WP_REST_Request)

### Phase 4: Service Layer (Business Logic)

28. Create src/Services/MaintenanceService.php
    - Constructor: Inject Settings model
    - shouldShowMaintenance() - Check if enabled and user can't bypass
    - canBypass(user) - Check capabilities and filters
    - render() - Load maintenance view, set 503 status
    - setStatus() - Set HTTP headers (503, Retry-After)

29. Update Bootstrap to register MaintenanceService in container

30. Test: Unit tests for service methods (mock Settings, mock WP_User)

### Phase 5: Middleware (Authorization)

31. Create src/Middleware/AuthMiddleware.php
    - handle(request, capability) - Authorize request
    - unauthorized() - Return 401 response

32. Update Router to support middleware on routes

33. Test: Integration test for middleware authorization

### Phase 6: MVC - Views (Presentation)

**Admin View:**

34. Create src/Views/Admin/index.php
    - Clean HTML structure
    - Div with id="smooth-maintenance-admin" (React mount point)
    - No business logic, just presentation
    - Enqueue point for AdminController

**Frontend View:**

35. Create src/Views/Frontend/maintenance.php
    - Full-height centered layout
    - Variables: $site_name, $site_url, $logo_url, $message
    - Inline CSS, responsive
    - Clean, minimal design

36. Test: Manually verify views render correctly

### Phase 7: REST API Routes (Router Integration)

37. Update Router to register routes in rest_api_init
    - Route::get('settings', [MaintenanceController::class, 'index'], ['auth'])
    - Route::post('settings', [MaintenanceController::class, 'update'], ['auth'])
    - Namespace: smooth-maintenance/v1
    - Middleware: ['auth' => AuthMiddleware]

38. Update Bootstrap to initialize Router and register routes

39. Test: Use Postman/curl to test endpoints
    - GET /wp-json/smooth-maintenance/v1/settings
    - POST /wp-json/smooth-maintenance/v1/settings
    - Verify 401 without auth, 200 with auth

### Phase 8: WordPress Integration (Hooks)

40. Update Bootstrap to register WordPress hooks via Loader:
    - admin_menu → AdminController::registerMenu()
    - admin_enqueue_scripts → AdminController::enqueueAssets()
    - template_redirect (priority 1) → MaintenanceService::render()
    - rest_api_init → Router::register()

41. Test: Verify hooks fire correctly in WordPress

### Phase 9: React Admin Interface

**Store (State Management):**

42. Create package.json (@wordpress/scripts, @wordpress/components, @wordpress/data)
43. Run `npm install`
44. Create assets/admin/src/store/reducer.js (state: settings, isSaving, hasLoaded)
45. Create assets/admin/src/store/actions.js (setSettings, setSaving, updateSettings)
46. Create assets/admin/src/store/selectors.js (getSettings, isMaintenanceEnabled, isSaving)
47. Create assets/admin/src/store/resolvers.js (getSettings resolver - auto-fetch)
48. Create assets/admin/src/store/index.js (register store with @wordpress/data)

**Components:**

49. Create assets/admin/src/components/Header.js (presentational)
50. Create assets/admin/src/components/MaintenanceToggle.js (connected: useSelect, useDispatch)
51. Create assets/admin/src/App.js (layout container: Header + MaintenanceToggle)
52. Create assets/admin/src/index.js (register store, render App into mount point)

**Styles:**

53. Create assets/admin/src/styles/admin.scss (admin UI styles)

**Build:**

54. Run `npm run build`
55. Verify build/ directory created (index.js, index.css, index.asset.php)

56. Test: Navigate to admin page (/wp-admin/admin.php?page=smooth-maintenance)
    - Verify React app loads
    - Verify toggle switch renders
    - Test toggle on/off
    - Verify saving state
    - Verify success notices

### Phase 10: Frontend Maintenance Mode Integration

57. Update MaintenanceService::render() to:
    - Check Settings::isMaintenanceEnabled()
    - Call canBypass() - if true, return early
    - Call setStatus() - HTTP 503
    - Load view: Views/Frontend/maintenance.php
    - Exit

58. Add theme override support:
    - Check {theme}/smooth-maintenance/maintenance.php
    - Fallback to plugin view

59. Test:
    - Enable maintenance mode via admin toggle
    - Logout, verify maintenance page shows (503 status)
    - Login as admin, verify bypass works (see normal site)
    - Test with different themes

### Phase 11: Testing Infrastructure

**PHPUnit (Backend):**

60. Create phpunit.xml (bootstrap, testsuites: unit, integration)
61. Create tests/php/bootstrap.php (load WordPress test environment)
62. Create tests/php/Unit/ContainerTest.php (test DI container)
63. Create tests/php/Unit/BaseModelTest.php (test model methods)
64. Create tests/php/Unit/SettingsTest.php (test Settings model)
65. Create tests/php/Unit/MaintenanceServiceTest.php (test service logic)
66. Create tests/php/Integration/ApiTest.php (test REST endpoints)

**Vitest (Frontend):**

67. Create tests/js/vitest.config.js (jsdom environment, coverage)
68. Create tests/js/store/actions.test.js (test Redux actions)
69. Create tests/js/store/selectors.test.js (test Redux selectors)
70. Create tests/js/components/MaintenanceToggle.test.js (component test)

71. Run tests:
    - `composer test` (PHPUnit)
    - `npm test` (Vitest)
    - Verify all pass, coverage >80%

### Phase 12: CI/CD Pipeline

72. Create .github/workflows/ci.yml
    - Matrix: PHP 8.0/8.1/8.2/8.3, WordPress 6.0+
    - Jobs: PHPCS, PHPUnit, ESLint, Vitest
    - Code coverage reports

73. Push to GitHub, verify all checks pass

### Phase 13: Documentation & Polish

74. Create docs/architecture.md (detailed MVC architecture documentation)
75. Create docs/mvc-guide.md (guide to MVC patterns used)
76. Update README.md (installation, development setup, MVC structure)
77. Add PHPDoc blocks to all classes/methods
78. Add JSDoc to complex React functions
79. Run linters: `composer run phpcs`, `npm run lint:js`
80. Fix all warnings

### Phase 14: Final Testing & Release

81. Fresh install test on clean WordPress installation
82. Multisite test (network activate, test on multiple sites)
83. Browser compatibility test (Chrome, Firefox, Safari, Edge)
84. Mobile responsive test (admin + maintenance page)
85. Performance audit:
    - Database query count (should be minimal)
    - Page load time with maintenance mode on/off
    - Admin UI load time
86. Security audit:
    - Verify all capabilities checked
    - Verify nonce validation
    - Verify input sanitization
    - Verify output escaping
87. Accessibility audit (WCAG AA compliance)
88. Final code review
89. Tag v1.0.0
90. Create release notes

---

## WordPress Integration Points

### Hooks Used

| Hook | Priority | File | Purpose |
|------|----------|------|---------|
| `plugins_loaded` | 10 | bootstrap.php | Initialize plugin |
| `admin_menu` | 10 | Admin/Menu.php | Register admin page |
| `admin_enqueue_scripts` | 10 | Admin/Assets.php | Load JS/CSS |
| `rest_api_init` | 10 | Api/MaintenanceController.php | Register REST routes |
| `template_redirect` | 1 | Frontend/MaintenanceMode.php | Show maintenance page |

### Services Registered in Container (MVC Components)

**Models:**
1. `Settings` → Settings::class (singleton)
2. `Subscriber` → Subscriber::class (singleton)

**Controllers:**
3. `MaintenanceController` → Controllers\Api\MaintenanceController::class
4. `AdminController` → Controllers\Admin\AdminController::class

**Services:**
5. `MaintenanceService` → Services\MaintenanceService::class (depends on Settings model)

**Core:**
6. `Router` → Core\Router::class (singleton)
7. `Loader` → Core\Loader::class (singleton)

**Middleware:**
8. `AuthMiddleware` → Middleware\AuthMiddleware::class

### Extensibility Filters (for future)

```php
apply_filters('smooth_maintenance_bypass', false, $user);
apply_filters('smooth_maintenance_template_path', $path);
apply_filters('smooth_maintenance_content', $html);
```

---

## Configuration Files

### composer.json
```json
{
    "name": "smooth-maintenance/smooth-maintenance",
    "type": "wordpress-plugin",
    "require": {
        "php": ">=8.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "yoast/phpunit-polyfills": "^2.0",
        "wp-coding-standards/wpcs": "^3.0"
    },
    "autoload": {
        "psr-4": {
            "SmoothMaintenance\\": "src/"
        }
    }
}
```

### package.json
```json
{
    "name": "smooth-maintenance",
    "scripts": {
        "start": "wp-scripts start",
        "build": "wp-scripts build",
        "test": "vitest"
    },
    "dependencies": {
        "@wordpress/api-fetch": "^7.0.0",
        "@wordpress/components": "^28.0.0",
        "@wordpress/data": "^10.0.0",
        "@wordpress/element": "^6.0.0",
        "@wordpress/i18n": "^5.0.0",
        "@wordpress/notices": "^5.0.0"
    },
    "devDependencies": {
        "@wordpress/scripts": "^27.0.0",
        "vitest": "^1.0.0"
    }
}
```

---

## Testing Strategy

### PHPUnit Tests (Backend)

**Unit Tests:**
- Container: Singleton, service registration, lazy loading
- SettingsRepository: CRUD operations, default values
- MaintenanceMode: Bypass logic, admin detection

**Integration Tests:**
- REST API: Authentication, validation, response format
- Database: Table creation, migrations

**Run:** `composer test` (requires WP test environment)

### Vitest Tests (Frontend)

**Store Tests:**
- Actions: setSettings, updateSettings API calls
- Selectors: getSettings, isMaintenanceEnabled
- Reducer: State updates

**Component Tests (future):**
- MaintenanceToggle: Render, user interaction, state updates

**Run:** `npm test`

### GitHub Actions

**On Push/PR:**
1. PHP CodeSniffer (WordPress standards)
2. PHPUnit (PHP 8.0/8.1/8.2/8.3, WP 6.0+)
3. ESLint (JavaScript linting)
4. Vitest (frontend tests)

---

## Performance Optimizations

1. **Settings cached in memory** - SettingsRepository caches get_all() result
2. **Admin assets only on plugin page** - Check current screen before enqueuing
3. **Lazy service instantiation** - Container creates services only when needed
4. **Early template_redirect hook** - Priority 1, exits before WordPress loads templates
5. **Minimal database queries** - Single option read if maintenance disabled
6. **Asset versioning** - filemtime() for cache busting on updates

---

## Security Measures

1. **Capability checks** - All admin functions require `manage_options`
2. **Nonce validation** - REST API uses `wp_rest` nonce automatically
3. **Input sanitization** - REST API validation schemas, `rest_sanitize_boolean()`
4. **SQL injection prevention** - Use `$wpdb->prepare()` for all queries
5. **XSS prevention** - Escape output with `esc_html()`, `esc_url()`; React auto-escapes
6. **CSRF protection** - Nonces on all state-changing operations

---

## Verification & Testing

### Activation Tests
- [ ] Plugin activates without errors
- [ ] Database table created: `{prefix}sm_subscribers`
- [ ] Default option created: `smooth_maintenance_settings`

### Admin Interface Tests
- [ ] Admin menu item appears: "Maintenance"
- [ ] React app loads without console errors
- [ ] Toggle switch renders and is interactive
- [ ] Saving shows loading state
- [ ] Success notice appears after save
- [ ] Settings persist after page reload

### REST API Tests
- [ ] GET /settings returns correct structure
- [ ] POST /settings validates boolean type
- [ ] Unauthorized users get 401 error
- [ ] Changes saved to database

### Frontend Tests
- [ ] Maintenance mode OFF: Site loads normally
- [ ] Maintenance mode ON + logged out: See maintenance page (503 status)
- [ ] Maintenance mode ON + logged in admin: Bypass works, see normal site
- [ ] Maintenance page responsive on mobile

### Testing Infrastructure
- [ ] `composer test` runs PHPUnit tests
- [ ] `npm test` runs Vitest tests
- [ ] All tests pass
- [ ] Code coverage >80%

### CI/CD Tests
- [ ] GitHub Actions workflow triggers on push
- [ ] PHPCS passes (WordPress standards)
- [ ] All PHPUnit tests pass in CI
- [ ] All Vitest tests pass in CI

### Multisite Tests
- [ ] Network activate works
- [ ] Each site has independent settings
- [ ] Maintenance mode per-site, not network-wide

### Browser Compatibility
- [ ] Chrome: Admin UI works, maintenance page displays
- [ ] Firefox: No JavaScript errors
- [ ] Safari: Styles render correctly

---

## Success Criteria for Iteration 1

**Iteration 1 is complete when:**

1. ✅ Admin can toggle maintenance mode on/off via React UI
2. ✅ Settings save to database via REST API
3. ✅ Non-admin users see maintenance page when enabled (503 status)
4. ✅ Admin users bypass maintenance page automatically
5. ✅ All PHPUnit tests pass
6. ✅ All Vitest tests pass
7. ✅ GitHub Actions CI pipeline green
8. ✅ Code follows WordPress standards (PHPCS passes)
9. ✅ Works on PHP 8.0+ and WordPress 6.0+
10. ✅ Multisite compatible

---

## Future Iterations (Beyond v1.0)

**Iteration 2: Templates & Countdown**
- 5 pre-designed templates (minimal, bold, countdown, newsletter, video)
- Template selector in admin
- Countdown timer with end date/time

**Iteration 3: Email Capture**
- Subscriber form on maintenance page
- List subscribers in admin
- Export to CSV
- Webhook integration (Mailchimp/ConvertKit)

**Iteration 4: Access Control**
- Preview link generation (secret tokens)
- IP whitelist
- User role bypass (not just admins)

**Iteration 5: Pro Features**
- Custom CSS editor
- Custom HTML content
- Advanced analytics
- A/B testing

---

## Development Workflow

### Daily Development
```bash
# Start dev server (watch mode)
npm run start

# Backend changes: refresh WordPress admin
# Frontend changes: auto-reload via HMR
```

### Before Commit
```bash
# Run linters
composer run phpcs
npm run lint:js

# Run tests
composer test
npm test

# Build production assets
npm run build
```

### Deployment
```bash
# Tag release
git tag v1.0.0
git push origin v1.0.0

# Create release ZIP (exclude: node_modules, tests, .git, docs)
```

---

## Critical Path Dependencies (MVC Architecture)

```
Foundation & Core Framework (Steps 1-17)
    ↓
Models (Steps 18-22) ────────────┐
    ↓                            │
Controllers (Steps 23-27)        │
    ↓                            │
Services (Steps 28-30)           │
    ↓                            │
Middleware (Steps 31-33)         │
    ↓                            │
Views (Steps 34-36) ←────────────┘
    ↓
Routes (Steps 37-39)
    ↓
WordPress Integration (Steps 40-41)
    ↓
React Admin (Steps 42-56) ←──────┐
    ↓                            │
Frontend Integration (Steps 57-59) ──┘
    ↓
Testing (Steps 60-71)
    ↓
CI/CD (Steps 72-73)
    ↓
Documentation (Steps 74-80)
    ↓
Final Testing & Release (Steps 81-90)
```

### Parallel Work Opportunities (MVC Benefits)

**After Step 22 (Models complete):**
- Developer A: Steps 23-27 (Controllers)
- Developer B: Steps 28-30 (Services)
- Developer C: Steps 31-33 (Middleware)

**After Step 36 (MVC backend complete):**
- Backend Developer: Steps 37-41 (Routes & WordPress hooks)
- Frontend Developer: Steps 42-56 (React admin interface)

**After Step 59 (Full integration complete):**
- QA Team: Steps 60-71 (Testing)
- DevOps: Steps 72-73 (CI/CD setup)
- Tech Writer: Steps 74-80 (Documentation)

**MVC architecture enables better parallelization than traditional WordPress plugin structure.**

---

## MVC Architecture Explained (Laravel-Inspired, WordPress-Adapted)

### The MVC Pattern

**Model-View-Controller** is a design pattern that separates application logic into three interconnected components:

1. **Model** - Data and business logic
2. **View** - Presentation layer (UI)
3. **Controller** - Request handling and flow control

### How MVC Fits WordPress

Traditional WordPress plugins mix concerns (data access, business logic, presentation all in one file). Our MVC approach separates these for better maintainability, testability, and scalability.

### Request Flow Diagram

```
User Request
    ↓
WordPress (template_redirect or REST API)
    ↓
Router (determines which controller/method)
    ↓
Controller (handles request)
    ↓
├─→ Validates input
├─→ Checks authorization (via Middleware)
├─→ Calls Service (business logic)
│       ↓
│   Service interacts with Model
│       ↓
│   Model reads/writes data (database or wp_options)
│       ↓
│   Returns data to Service
│       ↓
│   Service returns to Controller
│
└─→ Controller loads View (or returns JSON)
    ↓
View renders HTML (or Controller returns WP_REST_Response)
    ↓
Response sent to user
```

### Layer Responsibilities

#### Models (Data Layer)
**What they do:**
- Database interactions (read/write)
- Data validation
- Business rules related to data
- Relationships between entities

**What they DON'T do:**
- HTTP request handling
- User authentication
- Rendering HTML
- Complex business logic (use Services)

**Example: Settings Model**
```php
class Settings extends BaseModel {
    protected static $option_name = 'smooth_maintenance_settings';

    public static function get($key, $default = null) {
        $settings = get_option(self::$option_name, []);
        return $settings[$key] ?? $default;
    }

    public static function set($key, $value) {
        $settings = get_option(self::$option_name, []);
        $settings[$key] = $value;
        return update_option(self::$option_name, $settings);
    }

    public function validate($data) {
        // Validation rules
        return is_bool($data['maintenance_mode_enabled']);
    }
}
```

#### Controllers (Request Handlers)
**What they do:**
- Receive HTTP requests (REST API or admin pages)
- Validate request data
- Call appropriate Service methods
- Format responses (JSON or load Views)
- Handle errors

**What they DON'T do:**
- Direct database access (use Models)
- Complex calculations (use Services)
- Render HTML directly (use Views)

**Example: MaintenanceController**
```php
class MaintenanceController extends BaseController {
    protected $settings;

    public function __construct(Settings $settings) {
        $this->settings = $settings;
    }

    public function index(WP_REST_Request $request) {
        if (!$this->authorize('manage_options')) {
            return $this->error('Unauthorized', 401);
        }

        return $this->success([
            'maintenance_mode_enabled' => $this->settings->get('maintenance_mode_enabled'),
            'version' => $this->settings->get('version')
        ]);
    }

    public function update(WP_REST_Request $request) {
        if (!$this->authorize('manage_options')) {
            return $this->error('Unauthorized', 401);
        }

        $data = $request->get_json_params();

        if (!$this->validate($data, ['maintenance_mode_enabled' => 'boolean'])) {
            return $this->error('Invalid data', 400);
        }

        $this->settings->set('maintenance_mode_enabled', $data['maintenance_mode_enabled']);

        return $this->success(['message' => 'Settings updated']);
    }
}
```

#### Services (Business Logic)
**What they do:**
- Complex business logic
- Orchestrate multiple Models
- Implement business rules
- Provide clean API for Controllers

**What they DON'T do:**
- HTTP request handling (use Controllers)
- Direct response formatting
- Rendering views

**Example: MaintenanceService**
```php
class MaintenanceService {
    protected $settings;

    public function __construct(Settings $settings) {
        $this->settings = $settings;
    }

    public function shouldShowMaintenance() {
        // Business logic: when to show maintenance page
        if (!$this->settings->get('maintenance_mode_enabled')) {
            return false;
        }

        if ($this->canBypass(wp_get_current_user())) {
            return false;
        }

        return true;
    }

    public function canBypass($user) {
        // Business rule: who can bypass maintenance mode
        if (user_can($user, 'manage_options')) {
            return true;
        }

        return apply_filters('smooth_maintenance_bypass', false, $user);
    }

    public function render() {
        if (!$this->shouldShowMaintenance()) {
            return;
        }

        $this->setStatus();

        // Load view
        include PLUGIN_PATH . 'src/Views/Frontend/maintenance.php';
        exit;
    }

    protected function setStatus() {
        status_header(503);
        header('Retry-After: 3600');
    }
}
```

#### Views (Presentation)
**What they do:**
- Render HTML
- Display data passed from Controllers
- Minimal logic (loops, conditionals for display only)

**What they DON'T do:**
- Database queries
- Business logic
- Data manipulation

**Example: Admin View**
```php
<!-- src/Views/Admin/index.php -->
<div class="wrap">
    <h1><?php echo esc_html__('Smooth Maintenance', 'smooth-maintenance'); ?></h1>
    <div id="smooth-maintenance-admin"></div>
    <!-- React app mounts here -->
</div>
```

**Example: Maintenance View**
```php
<!-- src/Views/Frontend/maintenance.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?php echo esc_html($site_name); ?> - Maintenance</title>
    <style>/* Inline CSS */</style>
</head>
<body>
    <div class="maintenance-container">
        <h1><?php echo esc_html__("We'll be back soon!", 'smooth-maintenance'); ?></h1>
        <p><?php echo esc_html($message); ?></p>
    </div>
</body>
</html>
```

### Dependency Injection (DI)

Controllers and Services receive their dependencies via constructor injection:

```php
// In Bootstrap
$container->singleton(Settings::class, function() {
    return new Settings();
});

$container->bind(MaintenanceController::class, function($container) {
    return new MaintenanceController(
        $container->make(Settings::class)
    );
});

$container->bind(MaintenanceService::class, function($container) {
    return new MaintenanceService(
        $container->make(Settings::class)
    );
});

// Resolve with dependencies
$controller = $container->make(MaintenanceController::class);
// Settings is automatically injected
```

**Benefits:**
- Testability (easy to mock dependencies)
- Flexibility (swap implementations)
- Clarity (dependencies explicit in constructor)

### Router (Laravel-Style Routes)

Instead of manually registering REST routes in WordPress, we use a Router:

```php
// Traditional WordPress way:
register_rest_route('smooth-maintenance/v1', '/settings', [
    'methods' => 'GET',
    'callback' => [$controller, 'index'],
    'permission_callback' => [$controller, 'permissions']
]);

// Our MVC Router way:
Router::get('settings', [MaintenanceController::class, 'index'])
      ->middleware('auth');

Router::post('settings', [MaintenanceController::class, 'update'])
      ->middleware('auth');
```

**Benefits:**
- Cleaner, more readable
- Middleware support
- Automatic controller resolution from container
- Similar to Laravel routing (familiar to developers)

### Middleware (Authorization Layer)

Middleware intercepts requests before they reach controllers:

```php
class AuthMiddleware {
    public function handle($request, $capability = 'manage_options') {
        if (!current_user_can($capability)) {
            return new WP_Error('unauthorized', 'Unauthorized', ['status' => 401]);
        }

        return true; // Continue to controller
    }
}

// Usage in routes:
Router::post('settings', [MaintenanceController::class, 'update'])
      ->middleware('auth:manage_options');
```

### Why This Architecture?

**Compared to traditional WordPress plugins:**

| Aspect | Traditional WP Plugin | Our MVC Approach |
|--------|----------------------|------------------|
| Code Organization | Mixed concerns in single files | Separated by responsibility |
| Testing | Hard to unit test | Easy to unit test each layer |
| Scalability | Becomes messy with growth | Scales cleanly |
| Team Collaboration | Hard to parallelize work | Multiple devs work on different layers |
| Maintainability | Difficult to trace logic | Clear request flow |
| Onboarding | Need to understand entire codebase | Learn one layer at a time |

**Laravel inspiration adapted for WordPress:**
- We keep WordPress conventions (hooks, filters, options API)
- We add structure where WordPress lacks it (MVC separation)
- We use WordPress functions but organize them better
- We don't reinvent the wheel, we organize what exists

### File Structure Recap

```
Model: src/Models/Settings.php
├─ Extends: BaseModel
├─ Handles: Data access (wp_options)
└─ Used by: Services, Controllers

Service: src/Services/MaintenanceService.php
├─ Injects: Settings model
├─ Handles: Business logic (when to show maintenance, who can bypass)
└─ Used by: Controllers, WordPress hooks

Controller: src/Controllers/Api/MaintenanceController.php
├─ Extends: BaseController
├─ Injects: Settings model (or Service)
├─ Handles: HTTP requests, validation, authorization
└─ Returns: WP_REST_Response

View: src/Views/Frontend/maintenance.php
├─ Receives: Variables from Service/Controller
├─ Handles: HTML rendering
└─ Returns: HTML output

Router: Registered in Bootstrap
├─ Defines: REST API routes
├─ Maps: Routes to Controllers
├─ Applies: Middleware
└─ Registers: With WordPress (rest_api_init)
```

---

## Key Architectural Decisions Rationale

### Why MVC Architecture?
- **Separation of Concerns** - Each layer has single responsibility
- **Testability** - Easy to unit test Models, Services, Controllers independently
- **Scalability** - Add features without spaghetti code
- **Team Collaboration** - Multiple developers work on different layers simultaneously
- **Maintainability** - Clear request flow, easy to debug
- **Familiar Pattern** - Developers from Laravel/Rails/Django feel at home
- **WordPress Compatible** - We don't fight WordPress, we organize it better

### Why Custom Service Container?
- **No external dependencies** - Simpler deployment, no Composer bloat
- **WordPress-friendly** - Integrates naturally with hooks system
- **Laravel-inspired API** - bind(), singleton(), make() methods
- **Lightweight** - ~150-200 lines of code
- **Learning opportunity** - Team understands DI pattern deeply
- **Control** - We control the implementation and features

### Why @wordpress/data?
- **Native WordPress** - Consistent with Gutenberg
- **Built-in features** - Resolvers auto-fetch data
- **Future-proof** - WordPress core team maintains it
- **Developer familiarity** - WordPress devs already know it

### Why Hybrid Database?
- **Settings in wp_options** - Leverage WordPress caching, familiar API
- **Subscribers in custom table** - Better performance for large lists, easier exports
- **Best of both** - WordPress integration + scalability

### Why Top-Level Menu?
- **Visibility** - Maintenance mode is a primary function
- **Professional appearance** - Dedicated plugins deserve top-level menu
- **User expectations** - Competitors use top-level menus

---

## Notes for Implementation

- All strings use `__('text', 'smooth-maintenance')` for i18n
- Use `wp_kses_post()` for HTML output sanitization
- REST API responses use `WP_REST_Response` objects
- Asset handles prefixed: `smooth-maintenance-admin-js`
- CSS classes prefixed: `.sm-`, `.smooth-maintenance-`
- Option names prefixed: `smooth_maintenance_`
- Database tables prefixed: `{wpdb->prefix}sm_`
- WordPress coding standards enforced via PHPCS
- React components use functional style with hooks
- No class components in React code
- Prefer `const` over `let` in JavaScript
- Use arrow functions for callbacks

---

## Additional Resources

**WordPress Test Environment Setup:**
```bash
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

**Local Development:**
- Use Local by Flywheel (already set up at path in prompt)
- Or use `@wordpress/env`: `npx @wordpress/env start`

**Code Quality Tools:**
- PHPCS: `composer global require wp-coding-standards/wpcs`
- ESLint: Included in @wordpress/scripts
- Prettier: `npm run format`

---

## Final Checklist Before Implementation

- [ ] All architectural decisions documented
- [ ] Directory structure clear
- [ ] File responsibilities defined
- [ ] Service container design understood
- [ ] Database schema finalized
- [ ] REST API spec complete
- [ ] React component hierarchy clear
- [ ] Testing strategy defined
- [ ] CI/CD pipeline planned
- [ ] Implementation order sequenced
- [ ] Dependencies identified
- [ ] Success criteria defined

**Ready to implement!** 🚀
