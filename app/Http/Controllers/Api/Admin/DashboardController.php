<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $range = $request->get('range', 6);
        $filters = [];

        // Hierarchical Security Filter
        if ($user && !in_array($user->role, ['superadmin', 'admin', 'administrator'])) {
            if ($user instanceof \App\Models\AdminUser) {
                if ($user->role === 'admin_wilayah' && $user->kedeputian_wilayah) {
                    $filters['kedeputian_wilayah'] = $user->kedeputian_wilayah;
                } elseif ($user->kantor_cabang) {
                    $filters['kantor_cabang'] = $user->kantor_cabang;
                }
            } elseif ($user instanceof \App\Models\Member && $user->role === 'pengurus') {
                $user->load('kantorCabang');
                if ($user->kantorCabang) {
                    $filters['kantor_cabang'] = $user->kantorCabang->name;
                }
            }
        } else {
            // Superadmin manual filters from request
            if ($request->kedeputian_wilayah) $filters['kedeputian_wilayah'] = $request->kedeputian_wilayah;
            if ($request->kantor_cabang) $filters['kantor_cabang'] = $request->kantor_cabang;
        }

        $data = $this->dashboardService->getStats($range, $filters);

        return $this->successResponse('Data Dashboard', $data);
    }

    public function hubData(Request $request)
    {
        $user = $request->user();
        
        $memberQuery = \App\Models\Member::where('role', 'anggota');
        $pilQuery = \App\Models\Pil::query();
        $kelilingQuery = \App\Models\BpjsKeliling::query();
        
        if ($user && $user->role !== 'superadmin') {
            $userKC = trim($user->kantor_cabang ?? '');
            $userKW = trim($user->kedeputian_wilayah ?? '');

            if ($userKW) {
                // Filter Members by KW
                $memberQuery->whereHas('city.kantorCabang', function($q) use ($userKW) {
                    $q->whereHas('kedeputianWilayah', function($q2) use ($userKW) {
                        $q2->where('name', $userKW);
                    });
                });
                
                // Filter Activities by KW
                $pilQuery->where('kedeputian_wilayah', $userKW);
                $kelilingQuery->where('kedeputian_wilayah', $userKW);
            }

            if ($userKC && $user->role !== 'admin_wilayah') {
                $memberQuery->whereHas('city.kantorCabang', function($q) use ($userKC) {
                    $q->where('name', $userKC);
                });
                $pilQuery->where('kantor_cabang', $userKC);
                $kelilingQuery->where('kantor_cabang', $userKC);
            }
        }

        $totalMembers = $memberQuery->count();
        $pilCount = $pilQuery->count();
        $kelilingCount = $kelilingQuery->count();
        
        // Simplified impact counts for now
        $pilImpact = \App\Models\PilParticipant::whereIn('pil_id', (clone $pilQuery)->pluck('id'))->count();
        $kelilingImpact = \App\Models\BpjsKelilingParticipant::whereIn('bpjs_keliling_id', (clone $kelilingQuery)->pluck('id'))->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_members' => $totalMembers,
                'total_sessions' => $pilCount + $kelilingCount,
                'total_impact' => $pilImpact + $kelilingImpact
            ]
        ]);
    }
}
