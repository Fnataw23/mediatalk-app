<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('english_level')
                    ->options([
                        'A2' => 'A2',
                        'B1' => 'B1',
                        'B2' => 'B2',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('xp')
                    ->numeric()
                    ->required()
                    ->default(0),
                \Filament\Forms\Components\Toggle::make('is_admin')
                    ->label('Administrator Access'),
                \Filament\Forms\Components\Toggle::make('is_blocked')
                    ->label('Banned / Blocked Account'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')->sortable(),
                \Filament\Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->disk('public'),
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('english_level')
                    ->label('Level')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('xp')
                    ->label('XP')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('level')
                    ->label('Computed Level')
                    ->state(fn (User $record) => $record->level),
                \Filament\Tables\Columns\IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Admin')
                    ->sortable(),
                \Filament\Tables\Columns\ToggleColumn::make('is_blocked')
                    ->label('Blocked')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
