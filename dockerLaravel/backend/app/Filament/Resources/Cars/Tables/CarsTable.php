<?php

namespace App\Filament\Resources\Cars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('owner_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('brand')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('year'),
                TextColumn::make('plate_number')
                    ->searchable(),
                TextColumn::make('vin_number')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
                TextColumn::make('transmission')
                    ->badge(),
                TextColumn::make('fuel_type')
                    ->badge(),
                TextColumn::make('seats')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_per_day')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('mileage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_oil_change_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('insurance_expiry_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('inspection_expiry_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('road_tax_expiry_date')
                    ->date()
                    ->sortable(),
                ImageColumn::make('image_url'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
