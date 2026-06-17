<?php

namespace App\Console\Commands;

use App\Models\Annonce;
use App\Models\Appariement;
use App\Models\CycleCompensation;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MatchAnnouncements extends Command
{
    protected $signature = 'app:match-announcements';
    protected $description = 'Apparie deux annonces ENVOI aux flux inverses (compensation locale)';

    public function handle()
    {
        $this->info('Début du matching (ENVOI ↔ ENVOI)...');

        $envois = Annonce::where('type', 'ENVOI')
            ->where('statut', 'EN_ATTENTE')
            ->where('montant_source', '>', 0)
            ->orderBy('created_at')
            ->get();

        $nombreAppariements = 0;

        foreach ($envois as $envoiA) {
            if ($envoiA->montant_source <= 0) continue;

            $envoiB = Annonce::where('type', 'ENVOI')
                ->where('id', '!=', $envoiA->id)
                ->where('statut', 'EN_ATTENTE')
                ->where('montant_source', '>', 0)
                ->where('devise_source', $envoiA->devise_cible)
                ->where('devise_cible', $envoiA->devise_source)
                ->where('pays_source', $envoiA->pays_destination)
                ->where('pays_destination', $envoiA->pays_source)
                ->first();

            if (!$envoiB) continue;

            $tauxA = $envoiA->taux_change;
            $inverseTauxB = 1 / $envoiB->taux_change;
            $ecartTaux = abs($tauxA - $inverseTauxB) / $tauxA;
            if ($ecartTaux > 0.02) {
                $this->warn("Taux incompatible entre annonce {$envoiA->id} et {$envoiB->id}");
                continue;
            }

            $montantCompense = min($envoiA->montant_source, $envoiB->montant_source * $inverseTauxB);
            if ($montantCompense <= 0) continue;
            $montantCompenseB = $montantCompense / $inverseTauxB;

            // Vérifier doublon
            $existe = Appariement::where(function ($q) use ($envoiA, $envoiB) {
                $q->where('annonce_envoi_id', $envoiA->id)->where('annonce_reception_id', $envoiB->id);
            })->orWhere(function ($q) use ($envoiA, $envoiB) {
                $q->where('annonce_envoi_id', $envoiB->id)->where('annonce_reception_id', $envoiA->id);
            })->exists();

            if ($existe) {
                $this->warn("Appariement déjà existant entre {$envoiA->id} et {$envoiB->id}");
                continue;
            }

            DB::transaction(function () use ($envoiA, $envoiB, $montantCompense, $montantCompenseB, &$nombreAppariements) {
                $cycle = CycleCompensation::create([
                    'reference' => 'CYC-' . strtoupper(Str::random(8)),
                    'date_debut' => now(),
                    'statut' => 'OUVERT',
                    'montant_total_envoi' => $montantCompense,
                    'montant_total_reception' => $montantCompenseB,
                    'solde_net' => $montantCompense - $montantCompenseB,
                ]);

                Appariement::create([
                    'annonce_envoi_id' => $envoiA->id,
                    'annonce_reception_id' => $envoiB->id,
                    'montant_compense' => $montantCompense,
                    'montant_a_payer_emetteur' => $montantCompense,   // colonne réelle
                    'montant_a_payer_recepteur' => $montantCompenseB, // colonne réelle
                    'statut' => 'EN_ATTENTE_VALIDATION',
                    'reference' => 'APP-' . strtoupper(Str::random(8)),
                    'cycle_compensation_id' => $cycle->id,
                    'date_appariement' => now(),
                    'accepte_par_emetteur' => false,
                    'accepte_par_recepteur' => false,
                ]);

                foreach ([$envoiA->user, $envoiB->user] as $user) {
                    if ($user) {
                        Notification::create([
                            'users_id' => $user->id,
                            'type' => 'MATCHING',
                            'titre' => 'Nouvel appariement',
                            'message' => 'Un appariement a été trouvé. Connectez-vous pour accepter.',
                            'canal' => 'APP',
                            'priorite' => 'NORMALE',
                            'est_lu' => false,
                        ]);
                    }
                }

                $nombreAppariements++;
            });
        }

        // dd($montantCompense, $montantCompenseB);

        $this->info("$nombreAppariements appariement(s) créé(s).");
        return Command::SUCCESS;
    }
}