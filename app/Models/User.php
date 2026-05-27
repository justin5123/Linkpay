<?php

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wallet> $wallets
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Annonce> $annonces
 */


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
        'statut_compte',
        'pays',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function wallets()
{
    return $this->hasMany(Wallet::class, 'users_id');
}

public function transaction()
{
    return $this->hasMany(Transaction::class, 'users_id');
}

public function annonce()
{
    return $this->hasMany(Annonce::class, 'users_id');
}
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FINTECH RELATIONS
    |--------------------------------------------------------------------------
    */

    public function wallet()
    {
        return $this->hasMany(
            Wallet::class,
            'users_id'
        );
    }

    public function documentsKyc()
    {
        return $this->hasMany(
            DocumentKyc::class,
            'users_id'
        );
    }

    public function annonces()
    {
        return $this->hasMany(
            Annonce::class,
            'users_id'
        );
    }

    public function transactions()
    {
        return $this->hasMany(
            Transaction::class,
            'users_id'
        );
    }

    public function moyensPaiement()
    {
        return $this->hasMany(
            MoyenPaiement::class,
            'users_id'
        );
    }

    public function notifications()
    {
        return $this->hasMany(
            Notification::class,
            'users_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPORT RELATIONS
    |--------------------------------------------------------------------------
    */

    public function supportTickets()
    {
        return $this->hasMany(
            SupportTicket::class,
            'users_id'
        );
    }

    public function ticketsAssignes()
    {
        return $this->hasMany(
            SupportTicket::class,
            'assigne_a'
        );
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(
            MessageSupport::class,
            'expediteur_id'
        );
    }

    public function messagesRecus()
    {
        return $this->hasMany(
            MessageSupport::class,
            'destinataire_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SOCIAL RELATIONS
    |--------------------------------------------------------------------------
    */

    public function posts()
    {
        return $this->hasMany(
            Post::class,
            'users_id'
        );
    }

    public function comment()
    {
        return $this->hasMany(
            Comment::class,
            'users_id'
        );
    }   
    public function getFilamentName(): string
    {
        return trim($this->nom . ' ' . $this->prenom);
    }
    public function getNameAttribute(): string
    {
        return trim($this->nom . ' ' . $this->prenom);
    }
    public function likes()
    {
        return $this->hasMany(
            Like::class,
            'users_id'
        );
    }

    // Utilisateurs que je suis
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        );
    }

    // Utilisateurs qui me suivent
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'following_id',
            'follower_id'
        );
    }


    // Dans le modèle User, après les traits




    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isSupport(): bool
    {
        return $this->role === 'SUPPORT';
    }

    public function isClient(): bool
    {
        return $this->role === 'CLIENT';
    }
}
    
