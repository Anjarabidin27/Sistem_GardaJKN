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
        $query = AuditLog::with('actor');

        if ($request->actor) {
            $query->whereHas('actor', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->actor}%");
            })->orWhere('actor_id', 'LIKE', "%{$request->actor}%");
        }

        if ($request->action) {
            $query->where('action', 'LIKE', "%{$request->action}%");
        }

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return $this->successResponse('Daftar Audit Log', $logs);
    }
}
