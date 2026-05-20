<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
                $table->id();

                // Informations personnelles
                $table->string('nom');
                $table->string('prenom');

                $table->string('email')->unique();
                $table->string('telephone')->unique();

                $table->string('pays');
                $table->string('devise')->nullable();

                // Sécurité
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');

                // Gestion rôle (tout le monde est un utilisateur)
                $table->enum('role', [
                    'CLIENT',
                    'SUPPORT',
                    'CONFORMITE',
                    'FINANCE',
                    'ADMIN'
                ])->default('CLIENT');

                // Statut du compte
                $table->enum('statut_compte', [
                    'EN_ATTENTE',
                    'ACTIF',
                    'SUSPENDU',
                    'BLOQUE',
                    'KYC_REJETE'
                ])->default('EN_ATTENTE');

                 $table->enum('statut_kyc', [
                    'EN_ATTENTE', 
                    'VALIDE', 
                    'REJETE'
                ])->default('EN_ATTENTE');
                
                // Gestion risque/fraude
                $table->boolean('is_suspected_fraud')
                    ->default(false);

                $table->text('fraud_reason')
                    ->nullable();

                // Dernière connexion
                $table->timestamp('last_login_at')
                    ->nullable();

                $table->rememberToken();
                $table->timestamps();
            });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
