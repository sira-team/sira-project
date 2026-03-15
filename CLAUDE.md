<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.18
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v12
- laravel/pennant (PENNANT) - v1
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `pennant-development` — Use when working with Laravel Pennant the official Laravel feature flag package. Trigger whenever the query mentions Pennant by name or involves feature flags or feature toggles in a Laravel project. Tasks include defining feature flags checking whether features are active creating class based features in `app/Features` using Blade `@feature` directives scoping flags to users or tenants building custom Pennant storage drivers protecting routes with feature flags testing feature flags with Pest or PHPUnit and implementing A B testing or gradual rollouts with feature flags. Do not trigger for generic Laravel configuration authorization policies authentication or non Pennant feature management systems.
- `pest-testing` — Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, browser testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works.
- `tailwindcss-development` — Styles applications using Tailwind CSS v4 utilities. Activates when adding styles, restyling components, working with gradients, spacing, layout, flex, grid, responsive design, dark mode, colors, typography, or borders; or when the user mentions CSS, styling, classes, Tailwind, restyle, hero section, cards, buttons, or any visual/UI changes.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `vendor/bin/sail npm run build`, `vendor/bin/sail npm run dev`, or `vendor/bin/sail composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan Commands

- Run Artisan commands directly via the command line (e.g., `vendor/bin/sail artisan route:list`, `vendor/bin/sail artisan tinker --execute "..."`).
- Use `vendor/bin/sail artisan list` to discover available commands and `vendor/bin/sail artisan [command] --help` to check parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Debugging

- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.
- To execute PHP code for debugging, run `vendor/bin/sail artisan tinker --execute "your code here"` directly.
- To read configuration values, read the config files directly or run `vendor/bin/sail artisan config:show [key]`.
- To inspect routes, run `vendor/bin/sail artisan route:list` directly.
- To check environment variables, read the `.env` file directly.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use strict typing at the head of a `.php` file: `declare(strict_types=1);`.
- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== sail rules ===

# Laravel Sail

- This project runs inside Laravel Sail's Docker containers. You MUST execute all commands through Sail.
- Start services using `vendor/bin/sail up -d` and stop them with `vendor/bin/sail stop`.
- Open the application in the browser by running `vendor/bin/sail open`.
- Always prefix PHP, Artisan, Composer, and Node commands with `vendor/bin/sail`. Examples:
    - Run Artisan Commands: `vendor/bin/sail artisan migrate`
    - Install Composer packages: `vendor/bin/sail composer install`
    - Execute Node commands: `vendor/bin/sail npm run dev`
    - Execute PHP scripts: `vendor/bin/sail php [script]`
- View all available Sail commands by running `vendor/bin/sail` without arguments.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `vendor/bin/sail artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `vendor/bin/sail artisan list` and check their parameters with `vendor/bin/sail artisan [command] --help`.
- If you're creating a generic PHP class, use `vendor/bin/sail artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `vendor/bin/sail artisan make:model --help` to check the available options.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `vendor/bin/sail artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `vendor/bin/sail npm run build` or ask the user to run `vendor/bin/sail npm run dev` or `vendor/bin/sail composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/sail bin pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/sail bin pint --test --format agent`, simply run `vendor/bin/sail bin pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `vendor/bin/sail artisan make:test --pest {name}`.
- Run tests: `vendor/bin/sail artisan test --compact` or filter: `vendor/bin/sail artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.

=== tailwindcss/core rules ===

# Tailwind CSS

- Always use existing Tailwind conventions; check project patterns before adding new ones.
- IMPORTANT: Always use `search-docs` tool for version-specific Tailwind CSS documentation and updated code examples. Never rely on training data.
- IMPORTANT: Activate `tailwindcss-development` every time you're working with a Tailwind CSS or styling-related task.

=== filament/filament rules ===

## Filament

- Filament is used by this application. Follow the existing conventions for how and where it is implemented.
- Filament is a Server-Driven UI (SDUI) framework for Laravel that lets you define user interfaces in PHP using structured configuration objects. Built on Livewire, Alpine.js, and Tailwind CSS.
- Use the `search-docs` tool for official documentation on Artisan commands, code examples, testing, relationships, and idiomatic practices. If `search-docs` is unavailable, refer to https://filamentphp.com/docs.

### Artisan

- Always use Filament-specific Artisan commands to create files. Find available commands with the `list-artisan-commands` tool, or run `php artisan --help`.
- Always inspect required options before running a command, and always pass `--no-interaction`.

### Patterns

Always use static `make()` methods to initialize components. Most configuration methods accept a `Closure` for dynamic values.

Use `Get $get` to read other form field values for conditional logic:

<code-snippet name="Conditional form field visibility" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options(CompanyType::class)
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),

</code-snippet>

Use `state()` with a `Closure` to compute derived column values:

<code-snippet name="Computed table column value" lang="php">
use Filament\Tables\Columns\TextColumn;

TextColumn::make('full_name')
    ->state(fn (User $record): string => "{$record->first_name} {$record->last_name}"),

</code-snippet>

Actions encapsulate a button with an optional modal form and logic:

<code-snippet name="Action with modal form" lang="php">
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

Action::make('updateEmail')
    ->schema([
        TextInput::make('email')
            ->email()
            ->required(),
    ])
    ->action(fn (array $data, User $record) => $record->update($data))

</code-snippet>

### Testing

Always authenticate before testing panel functionality. Filament uses Livewire, so use `Livewire::test()` or `livewire()` (available when `pestphp/pest-plugin-livewire` is in `composer.json`):

<code-snippet name="Table test" lang="php">
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->assertCanSeeTableRecords($users)
    ->searchTable($users->first()->name)
    ->assertCanSeeTableRecords($users->take(1))
    ->assertCanNotSeeTableRecords($users->skip(1));

</code-snippet>

<code-snippet name="Create resource test" lang="php">
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

livewire(CreateUser::class)
    ->fillForm([
        'name' => 'Test',
        'email' => 'test@example.com',
    ])
    ->call('create')
    ->assertNotified()
    ->assertRedirect();

assertDatabaseHas(User::class, [
    'name' => 'Test',
    'email' => 'test@example.com',
]);

</code-snippet>

<code-snippet name="Testing validation" lang="php">
use function Pest\Livewire\livewire;

livewire(CreateUser::class)
    ->fillForm([
        'name' => null,
        'email' => 'invalid-email',
    ])
    ->call('create')
    ->assertHasFormErrors([
        'name' => 'required',
        'email' => 'email',
    ])
    ->assertNotNotified();

</code-snippet>

<code-snippet name="Calling actions in pages" lang="php">
use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

livewire(EditUser::class, ['record' => $user->id])
    ->callAction(DeleteAction::class)
    ->assertNotified()
    ->assertRedirect();

</code-snippet>

<code-snippet name="Calling actions in tables" lang="php">
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->callAction(TestAction::make('promote')->table($user), [
        'role' => 'admin',
    ])
    ->assertNotified();

</code-snippet>

### Correct Namespaces

- Form fields (`TextInput`, `Select`, etc.): `Filament\Forms\Components\`
- Infolist entries (`TextEntry`, `IconEntry`, etc.): `Filament\Infolists\Components\`
- Layout components (`Grid`, `Section`, `Fieldset`, `Tabs`, `Wizard`, etc.): `Filament\Schemas\Components\`
- Schema utilities (`Get`, `Set`, etc.): `Filament\Schemas\Components\Utilities\`
- Actions (`DeleteAction`, `CreateAction`, etc.): `Filament\Actions\`. Never use `Filament\Tables\Actions\`, `Filament\Forms\Actions\`, or any other sub-namespace for actions.
- Icons: `Filament\Support\Icons\Heroicon` enum (e.g., `Heroicon::PencilSquare`)

### Common Mistakes

- **Never assume public file visibility.** File visibility is `private` by default. Always use `->visibility('public')` when public access is needed.
- **Never assume full-width layout.** `Grid`, `Section`, and `Fieldset` do not span all columns by default. Explicitly set column spans when needed.

</laravel-boost-guidelines>

# Sira App — CLAUDE.md

This file describes the architecture, conventions, and business logic of the Sira App. Each module has its own `CLAUDE.md` inside its directory with module-specific detail. Read this file first, then the relevant module file before writing any code.

## Module Documentation

- `Modules/Camp/CLAUDE.md` — camp registration, waitlist, room assignment, notifications
- `Modules/Expo/CLAUDE.md` — expo requests, station inventory, file downloads
- `Modules/Academy/CLAUDE.md` — global curriculum management and tenant academy access

---

## What This Project Is

A multi-tenant Laravel application for a network of Islamic educational Vereins across multiple cities. Each city is an independent tenant. There is no public-facing website. The only public surfaces are two Blade forms: camp registration and expo request.

---

## Stack

- **Laravel 12**
- **Filament 4.x** — all admin UI
- **coolsam/modules** (`savannabits/filament-modules`) — Filament-optimised module scaffolding built on top of nwidart/laravel-modules. Installs nwidart automatically. Each module gets its own Filament panel via `module:filament:panel`.
- **bezhansalleh/filament-shield** — access management via spatie/laravel-permission. Auto-generates policies per resource. Handles super_admin via gate interception.
- **spatie/laravel-permission** — roles and permissions, tenant mode enabled. Installed automatically as a dependency of filament-shield.
- **laravel/pennant** — feature flags for panel and module access
- **Laravel Mail** — all notifications, always queued
- **Laravel Storage** — private disk for expo digital materials
- **Blade** — public forms only, no Livewire on public routes

---

## Package Notes

### coolsam/modules

Packagist name is `coolsam/modules`, GitHub is `savannabits/filament-modules`.

```bash
composer require coolsam/modules
php artisan modules:install
```

Register once in the main panel:
```php
use Coolsam\Modules\ModulesPlugin;

->plugin(ModulesPlugin::make())
```

This auto-discovers all module plugins. Each module is scaffolded with:
```bash
php artisan module:make ModuleName
php artisan module:filament:install ModuleName   # interactive, sets up plugin + cluster
php artisan module:filament:panel ModuleName     # creates a dedicated panel inside the module
```

Use module-specific generators during development:
```bash
php artisan module:filament:resource
php artisan module:filament:page
php artisan module:filament:widget
```

### bezhansalleh/filament-shield

Setup per panel (run once per panel after resources exist):
```bash
php artisan shield:setup
php artisan shield:install {panel-id}
php artisan shield:generate --all --panel={panel-id}
```

Shield auto-generates:
- Policies for every Resource in the panel
- Permissions following the naming convention configured in `config/filament-shield.php`
- A `RoleResource` UI inside the panel for managing roles

Register in each panel:
```php
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

->plugin(FilamentShieldPlugin::make()->scopeToTenant(true))
```

`scopeToTenant(true)` is required for all tenant-scoped panels to ensure roles and permissions are correctly scoped to the current tenant.

The `super_admin` role bypasses all permission checks via gate interception. No policy methods are checked for this role.

For role assignment forms in tenancy context, use `syncWithPivotValues` with `getPermissionsTenantId()`:
```php
Forms\Components\Select::make('roles')
    ->relationship('roles', 'name')
    ->saveRelationshipsUsing(function (Model $record, $state) {
        $record->roles()->syncWithPivotValues($state, [
            config('permission.column_names.tenant_foreign_key') => getPermissionsTenantId()
        ]);
    })
    ->multiple()
    ->preload()
    ->searchable()
```

Use `HasPageShield` and `HasWidgetShield` traits on pages and widgets to enforce permissions automatically.

---

## Filament Panels — All Six

### 1. Super Admin Panel

- **ID:** `super-admin`
- **Path:** `/super-admin`
- **Guard:** `web`
- **No tenant context**
- **Access:** `super_admin` role only (gate interception via Shield)
- **Purpose:** Create tenants, assign tenant owner, grant/revoke Pennant flags per Tenant, global user overview
- **Location:** `app/Providers/Filament/SuperAdminPanelProvider.php`

### 2. Tenant Admin Panel

- **ID:** `admin`
- **Path:** `/admin`
- **Guard:** `web`
- **Tenant model:** `Tenant`, slug field: `slug`
- **Access:** `tenant_admin` role
- **Purpose:** Invite users to the tenant, assign roles, manage tenant members
- **Location:** `app/Providers/Filament/AdminPanelProvider.php`
- **Registers:** `FilamentShieldPlugin::make()->scopeToTenant(true)`

### 3. Camp Panel

- **ID:** `camp`
- **Path:** `/camp`
- **Guard:** `web`
- **Tenant model:** `Tenant`
- **Access:** `camp_manager`, `tenant_admin`
- **Pennant:** none — always available to all tenants
- **Purpose:** All camp management resources
- **Location:** `Modules/Camp/app/Providers/Filament/CampPanelProvider.php`

### 4. Expo Panel

- **ID:** `expo`
- **Path:** `/expo`
- **Guard:** `web`
- **Tenant model:** `Tenant`
- **Access:** `expo_manager`, `tenant_admin`
- **Pennant:** `expo-panel` scoped to `Tenant` — check in panel middleware
- **Purpose:** Expo requests, planning, station inventory
- **Location:** `Modules/Expo/app/Providers/Filament/ExpoPanelProvider.php`

### 5. Academy Panel

- **ID:** `academy`
- **Path:** `/academy`
- **Guard:** `web`
- **Tenant model:** `Tenant`
- **Pennant:** `academy-panel` scoped to `Tenant` — check in panel middleware
- **Access:** all authenticated tenant users — but resources shown differ by role (see Academy CLAUDE.md)
- **Purpose:** Member dashboard, achievements, enrollments, ticket issuance
- **Location:** `Modules/Academy/app/Providers/Filament/AcademyPanelProvider.php`

### 6. Academy Content Panel

- **ID:** `academy-content`
- **Path:** `/academy-content`
- **Guard:** `web` (same users table)
- **No tenant context** — operates globally
- **Pennant:** `academy-content-management` scoped to `User`
- **Purpose:** Manage global curriculum — levels, sessions, quizzes, questions
- **Location:** `Modules/Academy/app/Providers/Filament/AcademyContentPanelProvider.php`

Panel middleware must check:
```php
abort_unless(Feature::for($request->user())->active('academy-content-management'), 403);
```

---

## Multi-Tenancy

Tenancy is implemented manually using Filament's built-in multi-tenancy support. No third-party tenancy package.

### Tenant Resolution

Resolved from subdomain via middleware registered in `bootstrap/app.php`.

```
bonn.sira-app.de  →  Tenant where slug = 'bonn'
koeln.sira-app.de →  Tenant where slug = 'koeln'
```

The resolved `Tenant` is bound to the container for the request lifecycle. Spatie tenant scope is set immediately after resolution:
```php
setPermissionsTenantId($tenant->id);
```

### BelongsToTenant Trait

All tenant-scoped models use this trait. It:
- Automatically sets `tenant_id` on creation from the resolved tenant
- Applies a global scope filtering by the current tenant's `tenant_id`

Never query tenant-scoped models without a resolved tenant. In commands or jobs, set the tenant explicitly before querying.

### Global Models

Models without `tenant_id` are shared across all tenants. Read-only from the tenant perspective:
- `AcademyLevel`, `AcademySession`
- `Quiz`, `QuizQuestion`, `QuizOption`

---

## Two Separate User Types

### Internal Users — `users` table

Verein members who log into Filament. Belong to a tenant. Have roles.

- Guard: `web`
- Model: `App\Models\User`
- Has `HasRoles` from Spatie, `HasFeatures` from Pennant

### Visitors — `visitors` table

External people who submit public forms. No Filament access. Will eventually have a separate login portal.

- Guard: `visitor`
- Model: `App\Models\Visitor`

Never mix these two. Do not use `users` for visitors. Do not use `visitors` for Filament users.

---

## Roles and Permissions

### Spatie Tenant Mode

`config/permission.php`:
```php
'tenants' => true,
'tenant_foreign_key' => 'tenant_id',
```

Before any permission check in a tenant panel: `setPermissionsTenantId($currentTenant->id)`
Before any permission check in a global panel: `setPermissionsTenantId(null)`

Handled in middleware, not manually per request.

### Seeded Roles Per Tenant

Seeded automatically via `TenantObserver` when a new `Tenant` is created:

| Role | Access |
|---|---|
| `tenant_admin` | Full access to everything in the tenant |
| `academy_manager` | Enrollments, tickets, progress tracking |
| `camp_manager` | Camps, registrations, rooms, notifications |
| `expo_manager` | Expos, stations, inventory, requests |
| `member` | Own Academy dashboard only |

Global role (seeded once, no tenant scope):

| Role | Access |
|---|---|
| `super_admin` | Everything, all tenants, all panels — bypasses all policy checks via Shield gate interception |

### Tenant-Defined Custom Roles

This is a core feature. Every Verein structures itself differently. The app does not enforce a fixed org structure beyond the seeded roles.

A `tenant_admin` can:
- Create new custom roles for their tenant via the Shield `RoleResource` UI
- Assign any combination of seeded permissions to those roles
- Assign roles to their members
- Rename or delete custom roles — not the seeded defaults

Custom roles are always tenant-scoped via Spatie tenant mode. Bonn's roles are invisible to Köln automatically.

Permissions are auto-generated by Shield per resource via `shield:generate`. Tenants assign existing permissions to their custom roles — they do not create new permissions.

### Multiple Roles Per User

A user can hold multiple roles. `tenant_admin` bypasses all permission checks.

---

## Pennant Feature Flags

All flags stored in the `features` table. Never in config or env.

### Scoped to `Tenant`

| Flag | Controls |
|---|---|
| `expo-panel` | Expo panel accessible, navigation visible |
| `academy-panel` | Academy panel accessible, navigation visible |

Camp panel is always available — no flag.

### Scoped to `User`

| Flag | Controls |
|---|---|
| `academy-content-management` | Access to Academy Content Panel |

### Artisan Commands

```bash
php artisan pennant:grant --tenant={id} --feature={flag}
php artisan pennant:revoke --tenant={id} --feature={flag}
php artisan pennant:grant --user={id} --feature={flag}
php artisan pennant:revoke --user={id} --feature={flag}
```

No UI for flag management. CLI only. Developer only.

---

## Observer: TenantObserver

Fires on `Tenant::created`. Must:
1. Seed all tenant roles scoped to the new `tenant_id`
2. If a tenant owner user ID is provided (from Super Admin Panel creation flow): assign `tenant_admin` role and send invitation email

---

## Coding Conventions

- **Enums** — all status and type columns use PHP-backed string enums in `App\Enums\` or the module's `Enums\` directory
- **Observers** — side effects live in observers (email on status change, role seeding on tenant creation)
- **Traits** — `BelongsToTenant` handles `tenant_id` scoping on all tenant models
- **Soft deletes** — all major tenant models use `SoftDeletes`
- **Mail** — all mailable classes are queued, all emails log to the relevant notification log table, tenant name rendered in every email header
- **Files** — never served via public URL, always through authenticated download routes using private storage disk
- **Shield policies** — never write policies manually. Always generate via `shield:generate`. If a resource needs a custom permission, define it in `config/filament-shield.php` under `custom_permissions`.

---

## Out of Scope — Do Not Build

- Visitor authentication and registration history dashboard
- Automatic waitlist promotion on payment confirmation
- Online payment — Überweisung only
- Inter-tenant material borrowing
- Quiz delivery outside of Filament
- Onboarding flows or in-app tutorials
- Blog or public content pages
- UI for managing Pennant flags
- Multi-language support — German only
