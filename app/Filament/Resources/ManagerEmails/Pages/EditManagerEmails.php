<?php

namespace App\Filament\Resources\ManagerEmails\Pages;

use App\Filament\Resources\ManagerEmails\ManagerEmailsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManagerEmails extends EditRecord
{
    protected static string $resource = ManagerEmailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
