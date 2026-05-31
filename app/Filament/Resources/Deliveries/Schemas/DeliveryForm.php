<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->label('Название')
                ->placeholder('Название')
                ->required()
                ->maxLength(255),

            FileUpload::make('icon')
                ->label('Иконка')
                ->image()
                ->disk('public')
                ->directory('delivery')
                ->visibility('public')
                ->nullable(),

            MarkdownEditor::make('description')
                ->label('Описание')
                ->required()
                ->columnSpanFull(),

        ]);
    }
}
