<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE notifications MODIFY type ENUM('INSCRIPTION','KYC','WALLET','ANNONCE','MATCHING','TRANSACTION','SUPPORT','SECURITE','FRAUDE','SYSTEME','SOCIAL') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE notifications MODIFY type ENUM('INSCRIPTION','KYC','WALLET','ANNONCE','MATCHING','TRANSACTION','SUPPORT','SECURITE','FRAUDE','SYSTEME') NOT NULL");
    }
};
