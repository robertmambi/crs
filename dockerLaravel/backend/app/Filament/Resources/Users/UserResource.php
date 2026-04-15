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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User')
                    ->schema([
                        TextInput::make('first_name')
                            ->required(),

                        TextInput::make('last_name')
                            ->required(),

                        TextInput::make('email')
                            ->email()
                            ->required(),

                        Select::make('role')
                            ->options([

                                'operator' => 'Operator',
                                'customer' => 'Customer',
                                'carowner' => 'Car Owner',
                            ])
                            ->required(),
                    ])
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
        return trim("{$record->first_name} {$record->last_name}") ?: $record->email;
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
