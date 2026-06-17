<?php

namespace App\Traits;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Request;

trait LogsAdminActions
{
    protected function logAction($action, $targetType = null, $targetId = null, $details = [])
    {
        if (!auth()->user() || auth()->user()->role !== 'ADMIN') {
            return;
        }

        AdminLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'details' => $details,
            'ip_address' => Request::ip(),
        ]);
    }
}