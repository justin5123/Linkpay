<?php

namespace App\Filament\Resources\TransactionCompenseeResource\Pages;

use App\Filament\Resources\TransactionCompenseeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionCompensees extends ListRecords
{
    protected static string $resource = TransactionCompenseeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
