<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompaniesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Название транспортной компании')
                ->placeholder('Транспортная компания')
                ->required()
                ->maxLength(255),
            ]);
    }
}
