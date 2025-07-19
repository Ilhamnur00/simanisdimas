<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('nip')->label('NIP')->required()->maxLength(20),
            TextInput::make('email')->email()->required()->maxLength(255),
            TextInput::make('password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                ->dehydrated(fn ($state) => filled($state))
                ->label('Password')
                ->maxLength(255),
            Select::make('roles')
                ->label('Role')
                ->relationship('roles', 'name')
                ->options(Role::all()->pluck('name', 'name'))
                ->searchable()
                ->preload()
                ->required()
                ->multiple(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable(),
            TextColumn::make('nip'),
            TextColumn::make('email')->searchable(),
            TextColumn::make('roles.name')->label('Role'),
        ])->filters([
            //
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
