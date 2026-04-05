<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\EmailTemplates\Tables;

use App\Enums\NotificationType;
use App\Models\EmailTemplate;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Modules\Camp\Mails\CampTemplateMail;
use Modules\Camp\Models\CampVisitor;

final class EmailTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('key')
                    ->label(__('Type'))
                    ->formatStateUsing(fn (NotificationType $state): string => $state->label())
                    ->sortable(),

                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label(__('Last Updated'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('send')
                    ->label(__('Test E-Mail'))
                    ->icon('heroicon-o-paper-airplane')
                    ->schema(fn (EmailTemplate $record): array => [
                        Select::make('camp_visitor_id')
                            ->label(__('Recipient'))
                            ->options(fn (): array => CampVisitor::query()
                                ->whereHas('camp', fn ($q) => $q->where('tenant_id', $record->tenant_id))
                                ->with(['visitor', 'camp'])
                                ->get()
                                ->mapWithKeys(fn (CampVisitor $cv): array => [
                                    $cv->id => $cv->visitor->name.' ('.$cv->camp->name.')',
                                ])
                                ->all())
                            ->searchable()
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->label(__('Override Email'))
                            ->email(),
                    ])
                    ->action(function (array $data, EmailTemplate $record): void {
                        $campVisitor = CampVisitor::with(['visitor', 'camp.tenant', 'visitor.guardians'])->findOrFail($data['camp_visitor_id']);
                        Mail::to($data['email'])->queue(new CampTemplateMail($record, $campVisitor));
                    }),
                EditAction::make(),
            ])
            ->defaultSort('key');
    }
}
