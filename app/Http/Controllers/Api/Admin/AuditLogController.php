<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $logs = AuditLog::with('actor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->successResponse('Daftar Audit Log', $logs);
    }
}
