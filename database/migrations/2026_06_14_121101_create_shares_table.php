// database/migrations/2024_01_01_000002_create_shares_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharesTable extends Migration
{
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'post_id'], 'unique_share');
            $table->index('created_at');
        });

        // Ajouter le compteur de partages dans posts
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('shares_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('shares_count');
        });
        Schema::dropIfExists('shares');
    }
}