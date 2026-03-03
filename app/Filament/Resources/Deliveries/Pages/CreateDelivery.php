<?php

namespace App\Filament\Resources\Deliveries\Pages;

use App\Filament\Resources\Deliveries\DeliveryResource;
use App\Models\Delivery;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateDelivery extends CreateRecord
{
    protected static string $resource = DeliveryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if (Delivery::query()->count() >= DeliveryResource::MAX_DELIVERIES) {
            throw ValidationException::withMessages([
                'title' => 'Можно создать только 3 записи доставки.',
            ]);
        }

        return parent::handleRecordCreation($data);
    }
}
