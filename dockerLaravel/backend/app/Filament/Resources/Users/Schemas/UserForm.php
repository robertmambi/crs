<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('password')
                    ->password(),
                TextInput::make('phone')
                    ->tel(),
                Select::make('role')
                    ->options([
            'admin' => 'Admin',
            'operator' => 'Operator',
            'customer' => 'Customer',
            'carowner' => 'Carowner',
        ])
                    ->required(),
                Select::make('status')
                    ->options([
            'active' => 'Active',
            'pending' => 'Pending',
            'suspended' => 'Suspended',
            'blocked' => 'Blocked',
        ])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                DateTimePicker::make('phone_verified_at'),
                Select::make('kyc_status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                    ->default('pending')
                    ->required(),
                Select::make('driver_status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                    ->default('pending')
                    ->required(),
                Select::make('id_type')
                    ->options(['driver_license' => 'Driver license', 'passport' => 'Passport']),
                TextInput::make('id_number'),
                FileUpload::make('id_image')
                    ->image(),
                Toggle::make('profile_completed')
                    ->required(),
                DateTimePicker::make('last_login_at'),
            ]);
    }
}
