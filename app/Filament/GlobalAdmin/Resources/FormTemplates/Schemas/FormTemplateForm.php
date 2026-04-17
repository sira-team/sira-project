<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\FormTemplates\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Camp\Filament\Resources\FormTemplates\Schemas\FormTemplateForm as CampFormTemplateForm;

final class FormTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')
                ->label(__('Tenant'))
                ->options(Tenant::query()->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->columnSpanFull(),
            TextInput::make('name')
                ->label(__('Template Name'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Textarea::make('description')
                ->label(__('Description'))
                ->nullable()
                ->rows(2)
                ->columnSpanFull(),
            Repeater::make('fields')
                ->label(__('Fields'))
                ->relationship('fields')
                ->orderColumn('order')
                ->columnSpanFull()
                ->schema(CampFormTemplateForm::fieldSchema())
                ->addActionLabel(__('Add field'))
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
        ]);
    }
}
