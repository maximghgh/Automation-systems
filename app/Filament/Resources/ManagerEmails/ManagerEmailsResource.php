<?php

namespace App\Filament\Resources\ManagerEmails;

use App\Filament\Resources\ManagerEmails\Pages\CreateManagerEmails;
use App\Filament\Resources\ManagerEmails\Pages\EditManagerEmails;
use App\Filament\Resources\ManagerEmails\Pages\ListManagerEmails;
use App\Filament\Resources\ManagerEmails\Schemas\ManagerEmailsForm;
use App\Filament\Resources\ManagerEmails\Tables\ManagerEmailsTable;
use App\Models\ManagerEmails;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManagerEmailsResource extends Resource
{
    protected static ?string $model = ManagerEmails::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Почты';
    protected static ?string $pluralModelLabel = 'Почты';
    protected static ?string $modelLabel = 'Почты';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return ManagerEmailsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManagerEmailsTable::configure($table);
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
            'index' => ListManagerEmails::route('/'),
            'create' => CreateManagerEmails::route('/create'),
            'edit' => EditManagerEmails::route('/{record}/edit'),
        ];
    }
}
