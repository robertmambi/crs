<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            // 👤 BASIC INFO
            Section::make('Basic Info')
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('last_name')->required(),

                    TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true),

                    TextInput::make('phone'),

                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context) => $context === 'create'),
                ]),

            // 🎭 ROLE & STATUS
            Section::make('Role & Status')
                ->schema([
                    Select::make('role')
                        ->options([
                            'admin' => 'Admin',
                            'operator' => 'Operator',
                            'customer' => 'Customer',
                            'carowner' => 'Car Owner',
                        ])
                        ->required(),

                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'pending' => 'Pending',
                            'suspended' => 'Suspended',
                            'blocked' => 'Blocked',
                        ])
                        ->required(),
                ]),

            // 🪪 KYC & DRIVER
            Section::make('Verification')
                ->schema([
                    Select::make('kyc_status')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                        ]),

                    Select::make('driver_status')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                        ]),

                    Select::make('id_type')
                        ->options([
                            'driver_license' => 'Driver License',
                            'passport' => 'Passport',
                        ]),

                    TextInput::make('id_number'),

                    TextInput::make('id_image'),
                ]),

            // 📊 SYSTEM
            Section::make('System')
                ->schema([
                    Toggle::make('profile_completed')
                        ->label('Profile Completed'),

                    DatePicker::make('email_verified_at'),
                    DatePicker::make('phone_verified_at'),
                    DatePicker::make('last_login_at'),
                ]),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
            TextColumn::make('name')
                ->label('Name')
                ->getStateUsing(fn ($record) => "{$record->name} {$record->last_name}")
                ->searchable(),

            TextColumn::make('email')->searchable(),

            TextColumn::make('phone'),

            TextColumn::make('role')->badge(),

            TextColumn::make('status')->badge(),

            TextColumn::make('kyc_status')->badge(),

            TextColumn::make('driver_status')->badge(),

            TextColumn::make('created_at')->dateTime(),
        ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'operator' => 'Operator',
                        'customer' => 'Customer',
                        'carowner' => 'Car Owner',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordTitle($record): string
    {
        return trim("{$record->name} {$record->last_name}") ?: $record->email;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


}
