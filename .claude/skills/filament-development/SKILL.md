# Filament v4 / v5 — Code Structure Skill

This skill defines how to write Filament resources, relation managers, pages, and widgets correctly for Filament v4+. Read this before writing any Filament code.

---

## Core Architecture Changes from v3

### Schemas replace Forms and Infolists
- All form and infolist components now live in `Filament\Schemas` namespace
- `form(Schema $schema)` replaces `form(Form $form)`
- `infolist(Schema $schema)` replaces `infolist(Infolist $infolist)`
- Mix form fields and infolist entries freely in the same schema
- Layout components (`Section`, `Grid`, `Tabs`) from `Filament\Schemas\Components`

### Unified Actions
- All actions from `Filament\Actions` — no more per-context action classes
- Same `Action` class works in tables, forms, infolists, pages

### Table method rename
- `actions()` on tables is now `recordActions()`

---

## Resource Directory Structure

Every resource lives in its own namespace directory:

```
Modules/Camp/app/Filament/Resources/
└── Camps/
    ├── CampResource.php
    ├── Pages/
    │   ├── ListCamps.php
    │   ├── CreateCamp.php
    │   └── EditCamp.php
    ├── Schemas/
    │   ├── CampForm.php          ← form schema class
    │   └── CampInfolist.php      ← infolist schema class (optional)
    ├── Tables/
    │   └── CampTable.php         ← table class
    ├── RelationManagers/
    │   ├── CampExpensesRelationManager.php
    │   └── CampRegistrationsRelationManager.php
    └── Actions/
        └── ConfirmRegistrationAction.php  ← reusable action (optional)
```

For relation managers, co-locate their schemas and tables inside the resource:
```
RelationManagers/
├── CampExpensesRelationManager.php
├── Schemas/
│   └── CampExpenseForm.php
└── Tables/
    └── CampExpensesTable.php
```

---

## Resource Class

```php
<?php

namespace Modules\Camp\Filament\Resources\Camps;

use Filament\Resources\Resource;use Filament\Schemas\Schema;use Filament\Tables\Table;use Modules\Camp\Filament\Resources\Camps\Schemas\CampForm;use Modules\Camp\Filament\Resources\Camps\Tables\CampTable;use Modules\Camp\Models\Camp;

class CampResource extends Resource
{
    protected static ?string $model = Camp::class;
    protected static ?string $navigationIcon = Heroicon::Tent; // use enum
    protected static ?string $navigationGroup = 'Camps';

    public static function form(Schema $schema): Schema
    {
        return CampForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CampInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            CampExpensesRelationManager::class,
            CampRegistrationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCamps::route('/'),
            'create' => Pages\CreateCamp::route('/create'),
            'edit' => Pages\EditCamp::route('/{record}/edit'),
            'view' => Pages\ViewCamp::route('/{record}'),
        ];
    }
}
```

---

## Schema Class (Form)

```php
<?php

namespace Modules\Camp\Filament\Resources\Camps\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\CampGenderPolicy;

class CampForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Basic Info')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('starts_at')
                        ->required(),
                    DatePicker::make('ends_at')
                        ->required()
                        ->afterOrEqual('starts_at'),
                    Select::make('target_group')
                        ->options(CampTargetGroup::class)
                        ->required(),
                    Select::make('gender_policy')
                        ->options(CampGenderPolicy::class)
                        ->required(),
                ]),
            Section::make('Registration')
                ->schema([
                    Toggle::make('registration_is_open'),
                    // ...
                ]),
        ]);
    }
}
```

---

## Table Class

```php
<?php

namespace Modules\Camp\Filament\Resources\Camps\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CampTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->date(),
                ToggleColumn::make('registration_is_open'),
            ])
            ->filters([
                // filters here
            ])
            ->recordActions([         // ← recordActions() not actions() in v4
                EditAction::make(),
            ])
            ->toolbarActions([
                // header actions
            ]);
    }
}
```

---

## Relation Manager

```php
<?php

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;use Filament\Schemas\Schema;use Filament\Tables\Table;use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\CampExpenseForm;use Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables\CampExpensesTable;

class CampExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'campExpenses';

    public function form(Schema $schema): Schema
    {
        return CampExpenseForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return CampExpensesTable::configure($table);
    }
}
```

---

## Actions

All actions from `Filament\Actions`. No namespace ambiguity.

```php
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;

// Custom action in a table
Action::make('confirm')
    ->label(__('Confirm'))
    ->icon(Heroicon::Check)
    ->color('success')
    ->requiresConfirmation()
    ->action(function (CampRegistration $record) {
        $record->update(['status' => CampRegistrationStatus::Confirmed]);
        // queue mail etc
    })
    ->visible(fn (CampRegistration $record) => $record->status === CampRegistrationStatus::Pending),
```

---

## Icons

Use the `Heroicon` enum instead of magic strings:

```php
use Filament\Support\Icons\Heroicon;

protected static ?string $navigationIcon = Heroicon::Tent;

Action::make('confirm')->icon(Heroicon::Check)
```

---

## Schema Components Namespaces

```php
// Layout
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

// Form fields
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;

// Infolist entries
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;

// Table columns
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;

// Table filters
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
```

---

## Enum Integration

Enums are first-class in v4. Pass them directly to `options()`:

```php
Select::make('target_group')
    ->options(CampTargetGroup::class)  // uses enum cases automatically
    ->required()

// Badge colors from enum
TextColumn::make('status')
    ->badge()
    ->color(fn (CampRegistrationStatus $state) => match($state) {
        CampRegistrationStatus::Confirmed => 'success',
        CampRegistrationStatus::Pending => 'warning',
        CampRegistrationStatus::Waitlisted => 'info',
        CampRegistrationStatus::Cancelled => 'danger',
    })
```

---

## Tenancy in Resources

Resources in tenant panels automatically scope to the current tenant via Filament's built-in tenancy. No manual scoping needed in resource queries. The `BelongsToTenant` global scope handles this.

For relation managers, the owner record is always the parent — no additional tenant scoping needed.

---

## Shield Integration

After writing all resources for a panel:

```bash
php artisan shield:generate --all --panel={panel-id}
```

This generates policies in `app/Policies/` for every resource model. Never write policies manually.

Add `HasPageShield` to pages and `HasWidgetShield` to widgets:

```php
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyPage extends Page
{
    use HasPageShield;
}
```

---

## RichEditor mergeTags

`mergeTags()` accepts a **static array** — never a bare closure as the first argument. To get dynamic tags from the record, use an associative array with human-readable labels returned from within a closure:

```php
// ✅ Correct — closure returns an array; associative for UI labels
RichEditor::make('body')
    ->mergeTags(fn (MyModel $record): array => collect($record->type->mergeTags())
        ->mapWithKeys(fn (string $tag): array => [
            $tag => ucwords(str_replace('_', ' ', $tag)),
        ])
        ->all())
    ->toolbarButtons([
        ['bold', 'italic', 'underline', 'strike', 'link'],
        ['h2', 'h3'],
        ['bulletList', 'orderedList', 'blockquote'],
        ['undo', 'redo'],
        ['mergeTags'],   // toolbar button to open the merge-tag picker
    ])

// ✅ Also valid — plain list, Filament uses keys as-is in content as {{ tag }}
->mergeTags(['visitor_name', 'camp_name'])

// ❌ Wrong — do not pass a bare closure without returning an array
->mergeTags(fn ($record) => $record->tags)   // closure must return array, not Collection etc.
```

Tag keys passed to `mergeTags()` are plain strings **without** `{{ }}`. Filament wraps them automatically when inserting into content.
Only use `h2` and `h3` in toolbar buttons — `h1` is not a valid toolbar button in Filament's RichEditor.

---

## Translations — REQUIRED

Every user-visible string on a Filament component **must** be wrapped in `__()`. This applies to all labels, headings, descriptions, placeholders, helper texts, hints, modal headings, empty-state text, and `Section::make` / `Tab::make` / `Fieldset::make` / `Stat::make` labels.

```php
// ✅ Correct
TextInput::make('name')->label(__('Name')),
Section::make(__('Basic Information'))->schema([...]),
Stat::make(__('Confirmed'), $count),
Action::make('confirm')->label(__('Confirm')),

// ❌ Wrong — bare string
TextInput::make('name')->label(__'Name')),
Section::make('Basic Information')->schema([...]),
```

Add every new string to both `lang/de.json` (German) and `lang/ar.json` (Arabic).

---

## DO NOT

- Do NOT use `Filament\Forms\Form` as a type hint — use `Filament\Schemas\Schema`
- Do NOT use `Filament\Infolists\Infolist` as a type hint — use `Filament\Schemas\Schema`
- Do NOT use `actions()` on tables — use `recordActions()`
- Do NOT import action classes from `Filament\Tables\Actions` or `Filament\Forms\Actions` — use `Filament\Actions`
- Do NOT inline large schemas in the resource class itself — always use Schema and Table classes
- Do NOT write policies manually — always use `shield:generate`
- Do NOT use bare strings in component labels — always wrap in `__()`
