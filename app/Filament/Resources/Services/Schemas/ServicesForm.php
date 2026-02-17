<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServicesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->label('Название услуги')
                ->placeholder('Проект')
                ->required()
                ->maxLength(255),

            FileUpload::make('image')
                ->label('Картинка')
                ->image()
                ->disk('public')
                ->directory('services')
                ->visibility('public')
                ->nullable(),

            Textarea::make('description')
                ->label('Описание')
                ->placeholder('Описание')
                ->required()
                ->rows(6)
                ->columnSpanFull(),

            MarkdownEditor::make('content')
                ->label('Характеристики')
                ->columnSpanFull()
                ->nullable(),
        ]);
    }
}
