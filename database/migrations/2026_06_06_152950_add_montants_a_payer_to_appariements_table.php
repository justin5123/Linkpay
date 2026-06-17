<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appariements', function (Blueprint $table) {
            $table->decimal('montant_a_payer_emetteur', 15, 2)->default(0)->after('montant_compense');
            $table->decimal('montant_a_payer_recepteur', 15, 2)->default(0)->after('montant_a_payer_emetteur');
        });
    }

    public function down()
    {
        Schema::table('appariements', function (Blueprint $table) {
            $table->dropColumn(['montant_a_payer_emetteur', 'montant_a_payer_recepteur']);
        });
    }
};