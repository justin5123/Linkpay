<?php

namespace App\Filament\Resources\DocumentKycResource\Pages;

use App\Filament\Resources\DocumentKycResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentKyc extends EditRecord
{
    protected static string $resource = DocumentKycResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
