<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                        'fill_gap' => 'Fill in the Gaps',
                        'match_words' => 'Match Words',
                    ])
                    ->required(),
                \Filament\Forms\Components\Textarea::make('question_text')
                    ->label('Question Text / Instructions')
                    ->helperText('Use brackets like [word] for Fill in the Gaps')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('answers')
                    ->relationship('answers')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('answer')
                            ->label('Answer / English Word')
                            ->required(),
                        \Filament\Forms\Components\Toggle::make('is_correct')
                            ->label('Is Correct?'),
                        \Filament\Forms\Components\TextInput::make('matching_translation')
                            ->label('Matching Translation (Russian - for match_words only)'),
                    ])
                    ->grid(2)
                    ->columnSpanFull(),
            ]);
    }
}
