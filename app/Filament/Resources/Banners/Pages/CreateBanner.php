<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use App\Models\Banner;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if (Banner::query()->count() >= BannerResource::MAX_BANNERS) {
            throw ValidationException::withMessages([
                'title' => 'Можно создать только 1 баннер.',
            ]);
        }

        return parent::handleRecordCreation($data);
    }
}
