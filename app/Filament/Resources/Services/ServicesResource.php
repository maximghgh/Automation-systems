<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateServices;
use App\Filament\Resources\Services\Pages\EditServices;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServicesForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Services;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicesResource extends Resource
{
    protected static ?string $model = Services::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Услуги';
    protected static ?string $pluralModelLabel = 'Услуги';
    protected static ?string $modelLabel = 'Услуги';

    protected static ?string $recordTitleAttribute = 'Services';

    public static function form(Schema $schema): Schema
    {
        return ServicesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateServices::route('/create'),
            'edit' => EditServices::route('/{record}/edit'),
        ];
    }
}
