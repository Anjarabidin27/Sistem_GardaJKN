<?php

namespace App\Services;

use App\Repositories\MemberRepository;

class DashboardService
{
    protected $memberRepo;

    public function __construct(MemberRepository $memberRepo)
    {
        $this->memberRepo = $memberRepo;
    }

    public function getStats(int $range = 6)
    {
        // Realtime: hitung langsung tanpa cache
        $totalMembers = $this->memberRepo->countAll();
        $growth = $this->memberRepo->countByMonth($range);

        // Calculate "New this month" from latest growth entry
        $newThisMonth = !empty($growth) ? end($growth)['total'] : 0;

        return [
            'summary' => [
                'total_members'   => $totalMembers,
                'new_this_month'  => $newThisMonth,
                'total_provinces' => $this->memberRepo->countActiveProvinces(),
                'total_logs'      => \App\Models\AuditLog::count(),
            ],
            'growth'       => $growth,
            'distribution' => [
                'gender'     => collect($this->memberRepo->getDistribution('gender'))->pluck('total', 'gender')->toArray(),
                'education'  => collect($this->memberRepo->getDistribution('education'))->pluck('total', 'education')->toArray(),
                'occupation' => collect($this->memberRepo->getDistribution('occupation'))->pluck('total', 'occupation')->toArray(),
                'age'        => [],
            ],
        ];
    }
}
