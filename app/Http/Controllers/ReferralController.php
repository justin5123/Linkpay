<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReferralTransaction;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $referrals = User::where('referred_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $transactions = ReferralTransaction::where('referrer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBonus = $user->referral_bonus;
        $totalReferrals = $user->referral_count;
        $referralLink = $user->referral_link;

        return view('referral.index', compact(
            'user', 'referrals', 'transactions',
            'totalBonus', 'totalReferrals', 'referralLink'
        ));
    }
}