<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\Car;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('owner_id')
                    ->numeric(),
                TextEntry::make('brand'),
                TextEntry::make('model'),
                TextEntry::make('year')
                    ->placeholder('-'),
                TextEntry::make('plate_number'),
                TextEntry::make('vin_number')
                    ->placeholder('-'),
                TextEntry::make('color')
                    ->placeholder('-'),
                TextEntry::make('transmission')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('fuel_type')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('seats')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('price_per_day')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('mileage')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('last_oil_change_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('insurance_expiry_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('inspection_expiry_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('road_tax_expiry_date')
                    ->date()
                    ->placeholder('-'),
                ImageEntry::make('image_url')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Car $record): bool => $record->trashed()),
            ]);
    }
}
