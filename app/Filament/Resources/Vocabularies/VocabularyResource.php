<?php

namespace App\Filament\Resources\Vocabularies;

use App\Filament\Resources\Vocabularies\Pages\ManageVocabularies;
use App\Models\Vocabulary;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VocabularyResource extends Resource
{
    protected static ?string $model = Vocabulary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('word')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('transcription')
                    ->placeholder('/example/')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('translation')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Textarea::make('explanation')
                    ->columnSpanFull()
                    ->maxLength(1000),
                \Filament\Forms\Components\Textarea::make('example')
                    ->columnSpanFull()
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('post.title')
                    ->label('Post')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('word')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('transcription')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('translation')->searchable()->sortable(),
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
            'index' => ManageVocabularies::route('/'),
        ];
    }
}
