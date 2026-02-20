<?php

namespace App\Filament\Resources\EmailTypes\Pages;

use App\Filament\Resources\EmailTypes\EmailTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmailType extends EditRecord
{
    protected static string $resource = EmailTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
