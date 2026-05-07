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

    public function getStats(int $range = 6, array $filters = [])
    {
        $user = auth()->user() ?? auth('admin')->user();

        // Realtime: hitung langsung tanpa cache
        $totalMembers = $this->memberRepo->countFiltered($filters);
        $growth = $this->memberRepo->countByMonthFiltered($range, $filters);

        // Calculate "New this month" from latest growth entry
        $newThisMonth = !empty($growth) ? end($growth)['total'] : 0;

        return [
            'summary' => [
                'total_members'   => $totalMembers,
                'new_this_month'  => $newThisMonth,
                'total_provinces' => $this->memberRepo->countActiveProvincesFiltered($filters),
                'total_logs'      => ($user && $user->role === 'superadmin') ? \App\Models\AuditLog::count() : 0,
            ],
            'growth'       => $growth,
            'distribution' => [
                'gender'     => collect($this->memberRepo->getDistributionFiltered('gender', $filters))->pluck('total', 'gender')->toArray(),
                'education'  => collect($this->memberRepo->getDistributionFiltered('education', $filters))->pluck('total', 'education')->toArray(),
                'occupation' => collect($this->memberRepo->getDistributionFiltered('occupation', $filters))->pluck('total', 'occupation')->toArray(),
                'branches'   => $this->memberRepo->getDistributionByBranch($filters),
                'age'        => [],
            ],
        ];
    }
}
