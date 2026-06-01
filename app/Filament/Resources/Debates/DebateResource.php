<?php

namespace App\Filament\Resources\Debates;

use App\Filament\Resources\Debates\Pages\ManageDebates;
use App\Models\Debate;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DebateResource extends Resource
{
    protected static ?string $model = Debate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(2000)
                    ->columnSpanFull(),
                \Filament\Forms\Components\DateTimePicker::make('starts_at')
                    ->label('Starts At'),
                \Filament\Forms\Components\DateTimePicker::make('ends_at')
                    ->label('Ends At'),
                \Filament\Forms\Components\Toggle::make('active')
                    ->label('Is Active Debate')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                \Filament\Tables\Columns\ToggleColumn::make('active')
                    ->label('Active')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('votes_count')
                    ->label('Votes Count')
                    ->state(fn (Debate $record) => $record->votes()->count()),
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
            'index' => ManageDebates::route('/'),
        ];
    }
}
