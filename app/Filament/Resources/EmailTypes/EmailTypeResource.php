<?php

namespace App\Filament\Resources\EmailTypes;

use App\Filament\Resources\EmailTypes\Pages\CreateEmailType;
use App\Filament\Resources\EmailTypes\Pages\EditEmailType;
use App\Filament\Resources\EmailTypes\Pages\ListEmailTypes;
use App\Filament\Resources\EmailTypes\Schemas\EmailTypeForm;
use App\Filament\Resources\EmailTypes\Tables\EmailTypesTable;
use App\Models\EmailType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmailTypeResource extends Resource
{
    protected static ?string $model = EmailType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Привязать почту';
    protected static ?string $pluralModelLabel = 'Привязать почту';
    protected static ?string $modelLabel = 'Привязать почту';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form(Schema $schema): Schema
    {
        return EmailTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailTypesTable::configure($table);
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
            'index' => ListEmailTypes::route('/'),
            'create' => CreateEmailType::route('/create'),
            'edit' => EditEmailType::route('/{record}/edit'),
        ];
    }
}
