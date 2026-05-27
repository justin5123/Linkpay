<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Annonce;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follow;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\MessageSupport;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. USERS
        $users = User::factory(20)->create();

        // 2. WALLETS
        foreach ($users as $user) {
            Wallet::factory()->create([
                'users_id' => $user->id,
            ]);
        }

        // 3. ANNONCES
        $users->each(function ($user) {
            Annonce::factory(20)->create([
                'users_id' => $user->id,
            ]);
        });

        // 4. POSTS
        $users->each(function ($user) {
            Post::factory(2)->create([
                'users_id' => $user->id,
            ]);
        });

        // 5. COMMENTS + LIKES + FOLLOWS
        Comment::factory(30)->create();
        Like::factory(50)->create();
        Follow::factory(30)->create();

        // 6. TRANSACTIONS
        Transaction::factory(50)->create();

        // 7. NOTIFICATIONS
        Notification::factory(50)->create();

        // 8. SUPPORT
        $users->each(function ($user) {
            SupportTicket::factory()->create([
                'users_id' => $user->id,
            ]);
        });

        MessageSupport::factory(30)->create();
    }
}