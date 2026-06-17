// database/migrations/2024_01_01_000001_fix_social_tables.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSocialTables extends Migration
{
    public function up()
    {
        // Éviter les doublons de likes
        Schema::table('likes', function (Blueprint $table) {
            $table->unique(['post_id', 'users_id'], 'unique_like');
        });

        // Éviter les doublons de follows (un utilisateur ne peut pas suivre deux fois le même)
        Schema::table('follows', function (Blueprint $table) {
            $table->unique(['follower_id', 'following_id'], 'unique_follow');
        });
    }

    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropUnique('unique_like');
        });
        Schema::table('follows', function (Blueprint $table) {
            $table->dropUnique('unique_follow');
        });
    }
}