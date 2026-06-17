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
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'pays',
        'devise',
        'password',
        'role',
        'statut_compte',
        'statut_kyc',
        'is_suspected_fraud',
        'fraud_reason',
        'last_login_at',
        // KYC columns
        'kyc_status',
        'identity_first_name',
        'identity_last_name',
        'identity_birth_date',
        'identity_birth_place',
        'identity_nationality',
        'address_street',
        'address_city',
        'address_postal_code',
        'address_country',
        'proof_of_address_path',
        'id_document_type',
        'id_document_number',
        'id_document_front_path',
        'id_document_back_path',
        'selfie_with_id_path',
        'additional_document_path',
        'kyc_rejection_reason',
        'kyc_submitted_at',
        'kyc_validated_at',
        'kyc_validated_by',
        // Parrainage
        'referral_code',
        'referred_by',
        'referral_bonus',
        'referral_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'identity_birth_date' => 'date',
        'kyc_submitted_at' => 'datetime',
        'kyc_validated_at' => 'datetime',
        'referral_bonus' => 'decimal:2',
        'referral_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | WALLET, TRANSACTIONS, PAYMENT METHODS
    |--------------------------------------------------------------------------
    */
    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'users_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'users_id');
    }

    public function moyensPaiements()
    {
        return $this->hasMany(MoyenPaiement::class, 'users_id');
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class, 'users_id');
    }

    public function moyensPaiement()
    {
        return $this->hasMany(MoyenPaiement::class, 'users_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ANNONCES
    |--------------------------------------------------------------------------
    */
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'users_id');
    }

    public function annonce()
    {
        return $this->hasMany(Annonce::class, 'users_id');
    }

    /*
    |--------------------------------------------------------------------------
    | KYC
    |--------------------------------------------------------------------------
    */
    public function documentsKyc()
    {
        return $this->hasMany(DocumentKyc::class, 'users_id');
    }

    public function kycValidator()
    {
        return $this->belongsTo(User::class, 'kyc_validated_by');
    }

    public function getCurrentKycStepAttribute()
    {
        $status = $this->kyc_status ?? 'NOT_STARTED';
        if (str_starts_with($status, 'STEP1')) return 1;
        if (str_starts_with($status, 'STEP2')) return 2;
        if (str_starts_with($status, 'STEP3')) return 3;
        if (str_starts_with($status, 'STEP4')) return 4;
        if ($status == 'COMPLETED') return 5;
        return 1; // NOT_STARTED → on commence à l'étape 1
    }

    public function canAccessKycStep($step)
    {
        $currentStep = $this->current_kyc_step;
        if ($step == 1) return true;
        if ($step == 2) return $currentStep >= 2;
        if ($step == 3) return $currentStep >= 3;
        if ($step == 4) return $currentStep >= 4;
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPORT
    |--------------------------------------------------------------------------
    */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'users_id');
    }

    public function ticketsAssignes()
    {
        return $this->hasMany(SupportTicket::class, 'assigne_a');
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(MessageSupport::class, 'expediteur_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(MessageSupport::class, 'destinataire_id');
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'users_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SOCIAL – POSTS, LIKES, COMMENTS, SHARES, FOLLOW
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasMany(Post::class, 'users_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'users_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'users_id');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'user_id');
    }

    // Follow/Unfollow
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function follow(User $user)
    {
        return $this->following()->toggle($user);
    }

    /*
    |--------------------------------------------------------------------------
    | SOCIAL – FRIENDSHIP
    |--------------------------------------------------------------------------
    */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'receiver_id');
    }

    public function friends()
    {
        return User::whereIn('id', function ($query) {
            $query->select('receiver_id')
                  ->from('friendships')
                  ->where('sender_id', $this->id)
                  ->where('status', 'accepted')
                  ->union(
                      $query->newQuery()->select('sender_id')
                            ->from('friendships')
                            ->where('receiver_id', $this->id)
                            ->where('status', 'accepted')
                  );
        });
    }

    public function pendingFriendRequests()
    {
        return $this->receivedFriendRequests()->where('status', 'pending');
    }

    public function hasFriendRequestFrom($userId)
    {
        return Friendship::where('sender_id', $userId)
            ->where('receiver_id', $this->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function isFriendWith($userId)
    {
        return Friendship::where(function ($query) use ($userId) {
            $query->where('sender_id', $this->id)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $this->id);
        })->where('status', 'accepted')->exists();
    }

    public function hasPendingFriendRequestTo($userId)
    {
        return $this->sentFriendRequests()->where('receiver_id', $userId)->where('status', 'pending')->exists();
    }

    public function hasPendingFriendRequestFrom($userId)
    {
        return $this->receivedFriendRequests()->where('sender_id', $userId)->where('status', 'pending')->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | PARRAINAGE (REFERRALS)
    |--------------------------------------------------------------------------
    */
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralTransactions()
    {
        return $this->hasMany(ReferralTransaction::class, 'referrer_id');
    }

    public function getReferralLinkAttribute()
    {
        return route('register', ['ref' => $this->referral_code]);
    }

    public function hasReferrer()
    {
        return $this->referred_by !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS / UTILITY
    |--------------------------------------------------------------------------
    */
    public function getFilamentName(): string
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

    public function getNameAttribute(): string
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

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

    /*
    |--------------------------------------------------------------------------
    | BOOT – GÉNÉRATION AUTOMATIQUE DU CODE DE PARRAINAGE
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                do {
                    $code = 'LIN-' . strtoupper(Str::random(6));
                } while (User::where('referral_code', $code)->exists());
                $user->referral_code = $code;
            }
        });
    }
}