<?php

namespace App\Helpers;

use App\Models\TransactionCompensee;

class LitigeHelper
{
    public static function countLitigesNonTraites()
    {
        return TransactionCompensee::where('statut', 'LITIGE')->count();
    }
}