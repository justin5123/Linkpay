<?php
// database/migrations/2026_06_05_000001_add_accept_fields_to_appariements.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appariements', function (Blueprint $table) {
            
        });
    }

    public function down()
    {
        Schema::table('appariements', function (Blueprint $table) {
            $table->dropColumn(['accepte_par_emetteur', 'accepte_par_recepteur', 'date_validation']);
        });
    }
};