<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->label('Заголовок')
                ->placeholder('Заголовок')
                ->required()
                ->maxLength(255),

            MarkdownEditor::make('description')
                ->label('Описание')
                ->required()
                ->columnSpanFull(),

            FileUpload::make('image')
                ->label('Картинка')
                ->disk('public')
                ->directory('banners')
                ->visibility('public')
                ->acceptedFileTypes(['image/jpg','image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                ->maxSize(2048) // 2MB
                ->nullable()
        ]);
    }
}
