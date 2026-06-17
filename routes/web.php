<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\AppariementController;
use App\Http\Controllers\PreuvePaiementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LitigeController;
use App\Http\Controllers\Social\PostController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Auth\OtpController;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SettingsController;


// ========================================================================
// ROUTES PUBLIQUES (sans authentification)
// ========================================================================

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/a-propos', [PublicController::class, 'about'])->name('about');
Route::get('/fonctionnalites', [PublicController::class, 'features'])->name('features');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('contact.submit');

// OTP (public)
Route::post('/send-otp', [OtpController::class, 'send'])->name('otp.send');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');

// Test email
Route::get('/test-mail', function () {
    $user = User::first();
    Mail::to($user->email)->send(new WelcomeMail($user));
    return 'Email envoyé';
});

// Test matching
Route::get('/test-match', function () {
    Artisan::call('app:match-announcements');
    return '<pre>' . Artisan::output() . '</pre>';
});

// Paramètres (profil, sécurité, préférences)

        
     // Paramètres (profil, sécurité, préférences) - Accessibles sans KYC
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::get('/profile', [SettingsController::class, 'profile'])->name('profile');
    Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::get('/security', [SettingsController::class, 'security'])->name('security');
    Route::put('/security', [SettingsController::class, 'updateSecurity'])->name('security.update');
    Route::get('/preferences', [SettingsController::class, 'preferences'])->name('preferences');
    Route::put('/preferences', [SettingsController::class, 'updatePreferences'])->name('preferences.update');
});

// ========================================================================
// ROUTES AUTHENTIFIÉES (auth + verified)
// ========================================================================

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // KYC (accessible même sans KYC validé, car c'est là qu'on le fait)
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::get('/status', [KycController::class, 'status'])->name('status');
        Route::get('/step/{step}', [KycController::class, 'showStep'])->name('step');
        Route::post('/step1', [KycController::class, 'postStep1'])->name('post.step1');
        Route::post('/step2', [KycController::class, 'postStep2'])->name('post.step2');
        Route::post('/step3', [KycController::class, 'postStep3'])->name('post.step3');
        Route::post('/step4', [KycController::class, 'postStep4'])->name('post.step4');
    });

    // Support (accessible même sans KYC validé)
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
        Route::post('/', [SupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{id}/message', [SupportTicketController::class, 'message'])->name('message');
        Route::patch('/{id}/close', [SupportTicketController::class, 'close'])->name('close');
        // Polling
        Route::get('/{id}/messages-json', [SupportTicketController::class, 'getMessagesJson'])->name('messages.json');
        Route::post('/messages/{id}/read', [SupportTicketController::class, 'markAsRead'])->name('message.read');
        Route::get('/unread-count', [SupportTicketController::class, 'unreadCount'])->name('unread');
    });

    // Notifications (accessible même sans KYC validé)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/json', [NotificationController::class, 'index'])->name('json');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/', [NotificationController::class, 'viewAll'])->name('index');
    });

    // ================================================================
    // ROUTES PROTÉGÉES PAR KYC (kyc.verified)
    // ================================================================

    Route::middleware(['kyc.verified'])->group(function () {

        // Wallet
        Route::prefix('wallet')->name('wallet.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::get('/deposit', [WalletController::class, 'depositForm'])->name('deposit');
            Route::post('/deposit', [WalletController::class, 'deposit'])->name('deposit.post');
            Route::get('/send', [WalletController::class, 'sendForm'])->name('send');
            Route::post('/send', [WalletController::class, 'send'])->name('send.post')->middleware('throttle:5,1');
            Route::get('/withdraw', [WalletController::class, 'withdrawForm'])->name('withdraw');
            Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw.post');
            Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');

            Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
                Route::get('/create', [WalletController::class, 'paymentMethodsCreate'])->name('create');
                Route::post('/', [WalletController::class, 'paymentMethodsStore'])->name('store');
                Route::get('/{id}/edit', [WalletController::class, 'paymentMethodsEdit'])->name('edit');
                Route::put('/{id}', [WalletController::class, 'paymentMethodsUpdate'])->name('update');
                Route::delete('/{id}', [WalletController::class, 'paymentMethodsDestroy'])->name('destroy');
            });
        });

        // Annonces
        Route::prefix('annonce')->name('annonce.')->group(function () {
            Route::get('/create', [AnnonceController::class, 'create'])->name('create');
            Route::post('/', [AnnonceController::class, 'store'])->name('store');
        });

        // Réseau social
        Route::prefix('social')->name('social.')->group(function () {
            Route::get('/', [PostController::class, 'timeline'])->name('timeline');
            Route::get('/search', [PostController::class, 'search'])->name('search');
            Route::get('/post/{post}', [PostController::class, 'showPost'])->name('post.show');
            Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
            Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
            Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
            Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('posts.share');
            Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
            Route::get('/profile/{user}', [PostController::class, 'profile'])->name('profile');
            Route::get('/profile/{user}/followers', [PostController::class, 'followers'])->name('followers');
            Route::get('/profile/{user}/following', [PostController::class, 'following'])->name('following');
            Route::post('/friend/request/{user}', [PostController::class, 'friendRequest'])->name('friend.request');
            Route::post('/friend/accept/{user}', [PostController::class, 'acceptFriendRequest'])->name('friend.accept');
            Route::post('/friend/reject/{user}', [PostController::class, 'rejectFriendRequest'])->name('friend.reject');
            Route::delete('/friend/cancel/{user}', [PostController::class, 'cancelFriendRequest'])->name('friend.cancel');
            Route::delete('/friend/unfriend/{user}', [PostController::class, 'unfriend'])->name('friend.unfriend');
        });

        
        // Parrainage
        Route::get('/parrainage', [ReferralController::class, 'index'])->name('referral.index');

        // Appariements
        Route::post('/appariement/{appariement}/accepter', [AppariementController::class, 'accepter'])->name('appariement.accepter');
        Route::post('/transaction/{transaction}/preuve', [PreuvePaiementController::class, 'store'])->name('preuve.store');
        Route::post('/transaction/{transaction}/confirmer', [TransactionController::class, 'confirmerReception'])->name('transaction.confirmer');
        Route::post('/transaction/{transaction}/litige', [LitigeController::class, 'signaler'])->name('transaction.litige');
    });

    // Admin Filament (routes déjà gérées par Filament)
});

// ========================================================================
// ROUTES D'AUTHENTIFICATION (Laravel Breeze)
// ========================================================================

require __DIR__.'/auth.php';