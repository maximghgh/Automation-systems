<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
                TextInput::make('name')
                    ->label('Название категории')
                    ->required()
                    ->maxLength(255),

                Repeater::make('subcategories')
                    ->label('Подкатегории')
                    ->relationship('subcategories')
                    ->orderColumn('sort_order')
                    ->itemLabel(fn (?array $state): ?string => $state['name'] ?? null)
                    ->collapsible()
                    ->cloneable()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название подкатегории')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
