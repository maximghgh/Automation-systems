<?php

namespace App\Filament\Resources\ManagerEmails\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ManagerEmailsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
                TextInput::make('email')
                    ->label('Почта менеджера')
                    ->email()
                    ->placeholder('manager@manager.ru')
                    ->required()
                    ->maxLength(255)
            ]);
    }
}
