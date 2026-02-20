<?php

namespace App\Filament\Resources\EmailTypes\Pages;

use App\Filament\Resources\EmailTypes\EmailTypeResource;
use App\Filament\Resources\ManagerEmails\ManagerEmailsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailTypes extends ListRecords
{
    protected static string $resource = EmailTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        return [
            ManagerEmailsResource::getUrl('index') => 'Почты',
            EmailTypeResource::getUrl('index') => 'Привязать Почту',
            'Список',
        ];
    }
}
