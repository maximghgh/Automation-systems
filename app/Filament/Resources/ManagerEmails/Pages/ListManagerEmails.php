<?php

namespace App\Filament\Resources\ManagerEmails\Pages;

use App\Filament\Resources\ManagerEmails\ManagerEmailsResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManagerEmails extends ListRecords
{
    protected static string $resource = ManagerEmailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('attach')
                ->label('Привязать')
                ->icon('heroicon-o-link')
                ->color('warning')
                ->url('/admin/email-types'),
            CreateAction::make(),
        ];
    }
}
