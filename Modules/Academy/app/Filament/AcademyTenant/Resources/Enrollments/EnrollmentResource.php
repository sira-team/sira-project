<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyTenant\Resources\Enrollments;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Academy\Filament\AcademyTenant\Resources\Enrollments\Pages\CreateEnrollment;
use Modules\Academy\Filament\AcademyTenant\Resources\Enrollments\Pages\EditEnrollment;
use Modules\Academy\Filament\AcademyTenant\Resources\Enrollments\Pages\ListEnrollments;
use Modules\Academy\Models\AcademyEnrollment;

final class EnrollmentResource extends Resource
{
    protected static ?string $model = AcademyEnrollment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->relationship('user', 'email')->required(),
            Select::make('academy_level_id')->relationship('level', 'title')->required(),
            DatePicker::make('started_at')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('level.title')->label('Level'),
                TextColumn::make('started_at')->dateTime(),
                TextColumn::make('completed_at')->dateTime(),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnrollments::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit' => EditEnrollment::route('/{record}/edit'),
        ];
    }
}
