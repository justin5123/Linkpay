<?php

namespace App\Exports;

use App\Models\TransactionCompensee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?: TransactionCompensee::query();
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Référence',
            'Payeur A',
            'Payeur B',
            'Montant A',
            'Devise A',
            'Montant B',
            'Devise B',
            'Statut',
            'Date de création',
        ];
    }

    public function map($row): array
    {
        return [
            $row->reference,
            $row->payeurA?->name,
            $row->payeurB?->name,
            $row->montant_a,
            $row->appariement?->annonceEnvoi?->devise_source ?? 'XAF',
            $row->montant_b,
            $row->appariement?->annonceReception?->devise_source ?? 'EUR',
            $row->statut,
            $row->created_at->format('d/m/Y H:i'),
        ];
    }
}