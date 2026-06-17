<?php

namespace App\Filament\Resources\LitigeResource\Pages;

use App\Filament\Resources\LitigeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLitige extends EditRecord
{
    protected static string $resource = LitigeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
