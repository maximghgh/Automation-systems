<?php

namespace App\Filament\Resources\Products\Schemas;


use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->label('Загаловок')
                ->placeholder('Загаловок')
                ->required()
                ->maxLength(255)
                ->afterStateUpdated(function (string $state, callable $set, callable $get) {
                    if (! $get('slug')) {
                        $set('slug', Str::slug($state)); // -> latin slug
                    }
                }),

            Hidden::make('slug')
                ->required(),

            Select::make('brand_id')
                ->label('Бренд')
                ->relationship('brand', 'name')   // берет name из таблицы brands
                ->searchable()
                ->preload()
                ->nullable(),

            Select::make('categories')
                ->label('Категории')
                ->relationship('categories', 'name') // берет name из таблицы categories
                ->multiple()
                ->searchable()
                ->preload(),

            FileUpload::make('image')
                ->label('Картинка')
                ->image()
                ->disk('public')
                ->directory('products')
                ->visibility('public')
                ->nullable(),

            Textarea::make('short_description')
                ->label('Краткое описание')
                ->placeholder('Описание')
                ->required()
                ->rows(6)
                ->columnSpanFull(),

            Toggle::make('is_new')
                ->label('Новинка')
                ->default(false)
                ->inline(false),

            MarkdownEditor::make('description')
                ->label('Описание')
                ->columnSpanFull()
                ->nullable(),

            MarkdownEditor::make('content')
                ->label('Характеристики')
                ->columnSpanFull()
                ->nullable(),

            Repeater::make('tabs')
                ->label('Дополнительные табы')
                ->relationship('tabs')
                ->orderColumn('sort_order')
                ->itemLabel(fn (?array $state): ?string => $state['title'] ?? null)
                ->collapsible()
                ->cloneable()
                ->schema([
                    TextInput::make('title')
                        ->label('Название таба')
                        ->required()
                        ->maxLength(255),

                    MarkdownEditor::make('content')
                        ->label('Содержимое')
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
