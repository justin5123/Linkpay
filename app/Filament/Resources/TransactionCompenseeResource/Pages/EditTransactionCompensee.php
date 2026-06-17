<?php

namespace App\Filament\Resources\TransactionCompenseeResource\Pages;

use App\Filament\Resources\TransactionCompenseeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionCompensee extends EditRecord
{
    protected static string $resource = TransactionCompenseeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
