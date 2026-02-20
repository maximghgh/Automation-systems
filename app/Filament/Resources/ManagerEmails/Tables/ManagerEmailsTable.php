<?php

namespace App\Filament\Resources\ManagerEmails\Tables;

use App\Models\EmailType;
use App\Models\ManagerEmails;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManagerEmailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Почта')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make()->label('Удалить'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
