<?php

namespace App\Services;
use App\Enums\OIPBusinessType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;


class OIPBusinessTypeFieldService
{
    public function getRequiredFields(OIPBusinessType $type): array
    {
        return match($type) {
            OIPBusinessType::NEW_ACCOUNT => [],
            OIPBusinessType::EXISTING_ACCOUNT_NEW_BUSINESS => ['origin', 'destination', 'mode'],
            OIPBusinessType::VALUE_ADD_SALE_EXISTING_BUSINESS => ['origin', 'destination', 'mode', 'charge_code'],
        };
    }

    public static function getCommonFields(): array
    {
        return [
            Select::make('business_type')
                ->options(function () {
                    return collect(OIPBusinessType::cases())->mapWithKeys(function ($type) {
                        return [$type->value => str_replace('_', ' ', ucwords(strtolower($type->name), '_'))];
                    })->toArray();
                })
                ->required()
                ->live(),
            TextInput::make('organization_code')
                ->required(),
        ];
    }


    public static function getBusinessTypeFields(OIPBusinessType $businessType): array
    {
        return match ($businessType) {
            OIPBusinessType::NEW_ACCOUNT => [
                Textarea::make('comment')
                    ->label('Comment')
                    ->placeholder('Enter any additional comments')
                    ->maxLength(255)
            ],
            OIPBusinessType::EXISTING_ACCOUNT_NEW_BUSINESS => [
                TextInput::make('origin')->required(),
                TextInput::make('destination')->required(),
                Select::make('mode')
                    ->options([
                        'air' => 'Air',
                        'sea' => 'Sea',
                        'rail' => 'Rail',
                        'road' => 'Road',
                        'brokerage' => 'Brokerage'
                    ])
                    ->required(),
                Textarea::make('comment')
                    ->label('Comment')
                    ->placeholder('Enter any additional comments')
                    ->maxLength(255)
            ],
            OIPBusinessType::VALUE_ADD_SALE_EXISTING_BUSINESS => [
                TextInput::make('shipment_number')
                    ->label('Shipment #')
                    ->placeholder('Enter the CW1 shipment number')
                    ->required()
                    ->columns(2),

                Select::make('value_add')
                    ->options([
                        'pickup' => 'Pickup',
                        'delivery' => 'Delivery',
                        'custom_clearance' => 'Custom Clearance',
                        'insurance' => 'Insurance',
                    ])
                    ->multiple()
                    ->columns(2),

                Textarea::make('comment')
                    ->label('Comment')
                    ->placeholder('Enter any additional comments')
                    ->maxLength(255)
                    ->columns(2)
            ],
        };
    }
}
