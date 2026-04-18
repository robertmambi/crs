<?php

namespace App\Filament\Resources\Cars;

use App\Filament\Resources\Cars\Pages\CreateCar;
use App\Filament\Resources\Cars\Pages\EditCar;
use App\Filament\Resources\Cars\Pages\ListCars;
use App\Filament\Resources\Cars\Pages\ViewCar;
use App\Filament\Resources\Cars\Schemas\CarForm;
use App\Filament\Resources\Cars\Schemas\CarInfolist;
use App\Filament\Resources\Cars\Tables\CarsTable;
use App\Models\Car;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $form->schema([
            Select::make('owner_id')
                ->relationship('owner', 'name')
                ->required(),

            TextInput::make('brand')->required(),
            TextInput::make('model')->required(),
            TextInput::make('year')->numeric(),

            TextInput::make('plate_number')->required(),
            TextInput::make('vin_number'),

            TextInput::make('color'),
            Select::make('transmission')
                ->options([
                    'auto' => 'Automatic',
                    'manual' => 'Manual',
                ]),

            TextInput::make('fuel_type'),
            TextInput::make('seats')->numeric(),

            TextInput::make('price_per_day')->numeric()->required(),

            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'maintenance' => 'Maintenance',
                    'inactive' => 'Inactive',
                ])
                ->required(),

            TextInput::make('mileage')->numeric(),

            DatePicker::make('insurance_expiry_date'),
            DatePicker::make('inspection_expiry_date'),
            DatePicker::make('road_tax_expiry_date'),
            DatePicker::make('last_oil_change_date'),

            TextInput::make('image_url'),

            Textarea::make('description'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCars::route('/'),
            'create' => CreateCar::route('/create'),
            'view' => ViewCar::route('/{record}'),
            'edit' => EditCar::route('/{record}/edit'),
        ];
    }
}
