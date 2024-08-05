<?php

namespace App\Filament\App\Resources\OIPRequestResource\Pages;

use App\Filament\App\Resources\OIPRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Services\OIPBusinessTypeFieldService;
use App\Enums\OIPBusinessType;
use App\Models\OIPRequest;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ManageOIPRequests extends ManageRecords
{
    protected static string $resource = OIPRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                
                        return $data;
                    })
        ];
    }
}