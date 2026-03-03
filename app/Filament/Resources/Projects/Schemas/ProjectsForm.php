<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->label('Название проекта')
                ->placeholder('Проект')
                ->required()
                ->maxLength(255)
                ->afterStateUpdated(function (string $state, callable $set, callable $get) {
                    if (! $get('slug')) {
                        $set('slug', Str::slug($state)); // -> latin slug
                    }
                }),

            Hidden::make('slug')
                ->required(),

            FileUpload::make('image')
                ->label('Картинка')
                ->image()
                ->disk('public')
                ->directory('projects')
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
