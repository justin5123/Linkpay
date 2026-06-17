<?php

namespace App\Filament\Resources\AppariementResource\Pages;

use App\Filament\Resources\AppariementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppariements extends ListRecords
{
    protected static string $resource = AppariementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
