<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('category_id')
                    ->relationship('category', 'title')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null
                    ),
                \Filament\Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(\App\Models\Post::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                \Filament\Forms\Components\MarkdownEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('media_type')
                    ->options([
                        'youtube' => 'YouTube Video Embed',
                        'vimeo' => 'Vimeo Video Embed',
                        'image' => 'Local / External Image',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('media_url')
                    ->label('Media URL / Embed Link')
                    ->helperText('For YouTube: https://www.youtube.com/embed/VIDEO_ID')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('level')
                    ->options([
                        'A2' => 'A2 - Elementary',
                        'B1' => 'B1 - Intermediate',
                        'B2' => 'B2 - Upper-Intermediate',
                    ])
                    ->required(),
            ]);
    }
}
