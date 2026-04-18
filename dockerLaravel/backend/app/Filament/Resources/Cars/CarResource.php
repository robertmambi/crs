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

// ✅ imports
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make('Basic Info')
                ->schema([
                    Select::make('owner_id')
                        ->relationship(
                            name: 'owner',
                            titleAttribute: 'email',
                            modifyQueryUsing: fn ($query) =>
                                $query->whereIn('role', ['carowner', 'operator'])
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->email} ({$record->role})")
                        ->preload() 
                        ->searchable()
                        ->required(),
                        // ->relationship(
                        //     name: 'owner',
                        //     titleAttribute: 'email',
                        //     modifyQueryUsing: fn ($query) => $query->where('role', ['carowner', 'operator'])
                        // )
                        // ->getOptionLabelFromRecordUsing(
                        //     fn ($record) => "{$record->email} ({$record->role})"
                        // )                        
                        // ->searchable()
                        // ->required(),

                    TextInput::make('brand')->required(),
                    TextInput::make('model')->required(),
                    TextInput::make('year')->numeric(),

                    TextInput::make('plate_number')->required(),
                    TextInput::make('vin_number'),
                ]),

            Section::make('Specifications')
                ->schema([
                    TextInput::make('color'),

                    Select::make('transmission')
                        ->options([
                            'auto' => 'Automatic',
                            'manual' => 'Manual',
                        ]),

                    Select::make('fuel_type')
                        ->options([
                            'gas' => 'Gas',
                            'diesel' => 'Diesel',
                            'electric' => 'Electric',
                            'hybrid' => 'Hybrid',
                        ]),

                    TextInput::make('seats')->numeric(),
                ]),

            Section::make('Pricing & Status')
                ->schema([
                    TextInput::make('price_per_day')
                        ->numeric()
                        ->required()
                        ->prefix('XCG'),

                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'maintenance' => 'Maintenance',
                            'inactive' => 'Inactive',
                        ])
                        ->required(),
                ]),

            Section::make('Maintenance')
                ->schema([
                    TextInput::make('mileage')->numeric(),

                    DatePicker::make('insurance_expiry_date'),
                    DatePicker::make('inspection_expiry_date'),
                    DatePicker::make('road_tax_expiry_date'),
                    DatePicker::make('last_oil_change_date'),
                ]),

            Section::make('Media & Notes')
                ->schema([
                    TextInput::make('image_url'),
                    Textarea::make('description'),
                ]),
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
