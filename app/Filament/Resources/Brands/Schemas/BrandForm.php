<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Название бренда')
                ->required()
                ->maxLength(255),
            FileUpload::make('image')
                ->label('Картинка')
                ->image()
                ->disk('public')
                ->directory('products')
                ->visibility('public')
                ->nullable(),
            ]);
    }
}
