<?php

namespace App\Filament\Resources\EmailTypes\Pages;

use App\Filament\Resources\EmailTypes\EmailTypeResource;
use App\Filament\Resources\ManagerEmails\ManagerEmailsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailType extends CreateRecord
{
    protected static string $resource = EmailTypeResource::class;

    public function getTitle(): string
    {
        return 'Привязать Почту';
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
