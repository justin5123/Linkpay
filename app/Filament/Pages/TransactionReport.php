<?php

namespace App\Filament\Pages;

use App\Models\TransactionCompensee;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

// ... dans la classe




class TransactionReport extends Page implements HasTable
{
    use InteractsWithTable;
     protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Rapport transactions';
    protected static ?string $navigationGroup = 'Gestion des transactions';  // ← à ajouter
    protected static ?string $title = 'Rapport des transactions';
    protected static string $view = 'filament.pages.transaction-report';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Exporter en CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    // Récupérer la requête du tableau (avec les filtres appliqués)
                    $query = $this->getTableQuery();
                    return Excel::download(new TransactionsExport($query), 'transactions_' . date('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TransactionCompensee::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('reference')->label('Référence')->searchable(),
                TextColumn::make('payeurA.name')->label('Payeur A'),
                TextColumn::make('payeurB.name')->label('Payeur B'),
                TextColumn::make('montant_a')->label('Montant A')->money('XAF'),
                TextColumn::make('montant_b')->label('Montant B')->money('EUR'),
                TextColumn::make('statut')->badge()
                    ->colors([
                        'success' => 'TERMINEE',
                        'danger' => 'LITIGE',
                        'secondary' => 'ANNULEE',
                    ]),
                TextColumn::make('created_at')->dateTime()->label('Date'),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options([
                        'TERMINEE' => 'Terminée',
                        'LITIGE' => 'Litige',
                        'ANNULEE' => 'Annulée',
                    ]),
                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from'),
                        \Filament\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}