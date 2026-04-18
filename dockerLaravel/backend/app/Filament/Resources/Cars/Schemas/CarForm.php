<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('owner_id')
                    ->required()
                    ->numeric(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('year'),
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('vin_number'),
                TextInput::make('color'),
                Select::make('transmission')
                    ->options(['auto' => 'Auto', 'manual' => 'Manual']),
                Select::make('fuel_type')
                    ->options(['gas' => 'Gas', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid']),
                TextInput::make('seats')
                    ->numeric(),
                TextInput::make('price_per_day')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['active' => 'Active', 'maintenance' => 'Maintenance', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
                TextInput::make('mileage')
                    ->numeric(),
                DatePicker::make('last_oil_change_date'),
                DatePicker::make('insurance_expiry_date'),
                DatePicker::make('inspection_expiry_date'),
                DatePicker::make('road_tax_expiry_date'),
                FileUpload::make('image_url')
                    ->image(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
