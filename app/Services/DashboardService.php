<?php

namespace App\Services;

use App\Repositories\MemberRepository;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    protected $memberRepo;

    public function __construct(MemberRepository $memberRepo)
    {
        $this->memberRepo = $memberRepo;
    }

    public function getStats(int $range = 6)
    {
        // Tidak menggunakan cache (Realtime) atau cache durasi 0
        return Cache::remember('dashboard_stats_' . $range, 0, function () use ($range) {
            return [
                'total_members' => $this->memberRepo->countAll(),
                'active_provinces' => $this->memberRepo->countActiveProvinces(),
                'total_audit_logs' => \App\Models\AuditLog::count(),
                'members_per_month' => $this->memberRepo->countByMonth($range),
                'gender_distribution' => $this->memberRepo->getDistribution('gender'),
                'education_distribution' => $this->memberRepo->getDistribution('education'),
                'occupation_distribution' => $this->memberRepo->getDistribution('occupation'),
            ];

        });
    }
}
