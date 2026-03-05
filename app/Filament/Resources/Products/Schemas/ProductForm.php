<?php

namespace App\Filament\Resources\Products\Schemas;


use App\Models\Category;
use App\Models\Subcategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
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

            Select::make('catalog_node')
                ->label('Категория / Подкатегория')
                ->options(fn (): array => self::getCatalogNodeOptions())
                ->searchable()
                ->preload()
                ->required(),

            Hidden::make('category_id'),

            Hidden::make('subcategory_id'),

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

            Repeater::make('tabs')
                ->label('Табы')
                ->relationship('tabs')
                ->orderColumn('sort_order')
                ->default([
                    [
                        'title' => 'Описание',
                        'content' => null,
                    ],
                ])
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

    public static function mapCatalogNodeToCategoryFields(array $data): array
    {
        $data['category_id'] = null;
        $data['subcategory_id'] = null;

        $catalogNode = $data['catalog_node'] ?? null;

        if (is_string($catalogNode) && str_starts_with($catalogNode, 'subcategory:')) {
            $subcategoryId = (int) Str::after($catalogNode, 'subcategory:');
            $subcategory = Subcategory::query()
                ->select(['id', 'category_id'])
                ->find($subcategoryId);

            if ($subcategory) {
                $data['subcategory_id'] = $subcategory->id;
                $data['category_id'] = $subcategory->category_id;
            }
        }

        if (is_string($catalogNode) && str_starts_with($catalogNode, 'category:')) {
            $categoryId = (int) Str::after($catalogNode, 'category:');

            $categoryExists = Category::query()
                ->whereKey($categoryId)
                ->doesntHave('subcategories')
                ->exists();

            if ($categoryExists) {
                $data['category_id'] = $categoryId;
                $data['subcategory_id'] = null;
            }
        }

        unset($data['catalog_node']);

        return $data;
    }

    public static function mapCategoryFieldsToCatalogNode(array $data): array
    {
        if (! empty($data['subcategory_id'])) {
            $data['catalog_node'] = 'subcategory:' . $data['subcategory_id'];

            return $data;
        }

        if (! empty($data['category_id'])) {
            $data['catalog_node'] = 'category:' . $data['category_id'];
        }

        return $data;
    }

    private static function getCatalogNodeOptions(): array
    {
        $categories = Category::query()
            ->with([
                'subcategories' => fn ($query) => $query
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->select(['id', 'category_id', 'name']),
            ])
            ->orderBy('name')
            ->get(['id', 'name']);

        $options = [];

        foreach ($categories as $category) {
            if ($category->subcategories->isEmpty()) {
                $options['category:' . $category->id] = $category->name;
                continue;
            }

            foreach ($category->subcategories as $subcategory) {
                $options['subcategory:' . $subcategory->id] = $category->name . ' / ' . $subcategory->name;
            }
        }

        return $options;
    }
}
