<?php

namespace App\Filament\Resources\Debates\Pages;

use App\Filament\Resources\Debates\DebateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDebates extends ManageRecords
{
    protected static string $resource = DebateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
