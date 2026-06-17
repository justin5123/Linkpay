<?php

namespace App\Filament\Resources\DocumentKycResource\Pages;

use App\Filament\Resources\DocumentKycResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentKycs extends ListRecords
{
    protected static string $resource = DocumentKycResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
