// database/migrations/2024_01_01_000003_add_social_indexes.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialIndexes extends Migration
{
    public function up()
    {
        // Timeline : posts des abonnements + propres
        Schema::table('posts', function (Blueprint $table) {
            $table->index(['users_id', 'created_at']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['post_id', 'created_at']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->index('users_id');
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->index(['follower_id', 'created_at']);
            $table->index(['following_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['users_id', 'created_at']);
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'created_at']);
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['users_id']);
        });
        Schema::table('follows', function (Blueprint $table) {
            $table->dropIndex(['follower_id', 'created_at']);
            $table->dropIndex(['following_id', 'created_at']);
        });
    }
}