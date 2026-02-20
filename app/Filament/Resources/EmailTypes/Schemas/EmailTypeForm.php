<?php

namespace App\Filament\Resources\EmailTypes\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EmailTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('type')
                ->label('Тип заявки')
                ->options(
                    \App\Models\EmailType::query()
                        ->orderBy('type')
                        ->pluck('type', 'type')
                )
                ->searchable()
                ->preload()
                ->required()
                ->native(false),

            CheckboxList::make('emails')
                ->label('Почты получателя')
                ->relationship('emails', 'email')
                ->columns(1)
                ->searchable(),
        ]);
    }
}
