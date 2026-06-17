<?php

namespace App\Filament\Resources\AppariementResource\Pages;

use App\Filament\Resources\AppariementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppariement extends EditRecord
{
    protected static string $resource = AppariementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
