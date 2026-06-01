<?php

namespace App\Filament\Resources\Vocabularies\Pages;

use App\Filament\Resources\Vocabularies\VocabularyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVocabularies extends ManageRecords
{
    protected static string $resource = VocabularyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
