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
        Schema::table('annonces', function (Blueprint $table) {

            $table->string('beneficiaire_nom')->after('pays_destination');

            $table->string('beneficiaire_telephone')
                ->after('beneficiaire_nom');

            $table->string('beneficiaire_email')
                ->nullable()
                ->after('beneficiaire_telephone');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            //
        });
    }
};
