<?php

namespace App\Repositories;

use App\Models\Member;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberRepository extends BaseRepository
{
    public function __construct(Member $model)
    {
        parent::__construct($model);
    }

    public function getFilteredList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->with(['province', 'city', 'district', 'kantorCabang.kedeputianWilayah']);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['search'] . '%')
                  ->orWhere('nik', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['province_id'])) {
            $query->where('province_id', $filters['province_id']);
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        // Region Filtering Logic (Forced Security Filter)
        $user = auth('admin')->user() ?? auth()->user();
        
        if ($user && !in_array($user->role, ['superadmin', 'administrator'])) {
            $userKW = trim($user->kedeputian_wilayah ?? '');
            $userKC = trim($user->kantor_cabang ?? '');
            
            if (($user->role === 'admin_wilayah' || $user->role === 'admin') && $userKW) {
                $query->whereHas('kantorCabang.kedeputianWilayah', function($q) use ($userKW) {
                    $q->whereRaw('TRIM(name) LIKE ?', ['%' . $userKW . '%']);
                });
            } elseif ($user->kantor_cabang_id) {
                $query->where('kantor_cabang_id', $user->kantor_cabang_id);
            } elseif ($userKC) {
                $query->whereHas('kantorCabang', function($q) use ($userKC) {
                    $q->whereRaw('TRIM(name) LIKE ?', ['%' . $userKC . '%']);
                });
            }
        } else {
            // Admin/Superadmin custom filters
            if (!empty($filters['kedeputian_wilayah'])) {
                $query->whereHas('kantorCabang.kedeputianWilayah', function($q) use ($filters) {
                    $q->where('name', $filters['kedeputian_wilayah']);
                });
            } else if (!empty($filters['kantor_cabang'])) {
                $query->whereHas('kantorCabang', function($q) use ($filters) {
                    $q->where('name', $filters['kantor_cabang']);
                });
            }
        }

        if (!empty($filters['only_deleted']) && $filters['only_deleted'] === 'true') {
            $query->onlyTrashed();
        } elseif (!empty($filters['only_deleted']) && $filters['only_deleted'] === 'pending') {
            $query->where('status_pengurus', 'pendaftaran_diterima');
        } else {
            $query->whereNull('deleted_at');
        }

        return $query->latest()->paginate($perPage);
    }

    protected function applyRegionalFilter($query, array $filters)
    {
        if (!empty($filters['kedeputian_wilayah'])) {
            $query->whereHas('kantorCabang.kedeputianWilayah', function($q) use ($filters) {
                $q->where('name', $filters['kedeputian_wilayah']);
            });
        } elseif (!empty($filters['kantor_cabang'])) {
            $query->whereHas('kantorCabang', function($q) use ($filters) {
                $q->where('name', $filters['kantor_cabang']);
            });
        }
        return $query;
    }

    public function countFiltered(array $filters): int
    {
        $query = $this->model->query();
        $this->applyRegionalFilter($query, $filters);
        return $query->count();
    }

    public function countByMonthFiltered(int $limit = 6, array $filters = []): array
    {
        $driver = \DB::getDriverName();
        $format = $driver === 'pgsql' ? "TO_CHAR(created_at, 'Mon YYYY')" : "DATE_FORMAT(created_at, '%b %Y')";

        $query = $this->model->selectRaw("$format as month, count(*) as total, MIN(created_at) as sort_date");
        $this->applyRegionalFilter($query, $filters);

        return $query->groupBy(\DB::raw($format))
            ->orderBy('sort_date', 'DESC')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }

    public function getDistributionFiltered(string $column, array $filters = []): array
    {
        $query = $this->model->select($column, \DB::raw('count(*) as total'));
        $this->applyRegionalFilter($query, $filters);
        return $query->groupBy($column)->get()->toArray();
    }

    public function countActiveProvincesFiltered(array $filters = []): int
    {
        $query = $this->model->query();
        $this->applyRegionalFilter($query, $filters);
        return $query->distinct('province_id')->count('province_id');
    }

    public function getPendingPengurus()
    {
        $query = $this->model->query()
            ->with(['province', 'city', 'kantorCabang.kedeputianWilayah'])
            ->where('is_interested_pengurus', true)
            ->where('status_pengurus', 'pendaftaran_diterima')
            ->where('role', 'anggota');

        // Robust Region Filter (Web & API)
        $user = auth()->user() ?? auth('admin')->user();
        if ($user && $user->role === 'admin_wilayah' && $user->kedeputian_wilayah) {
            $this->applyRegionalFilter($query, ['kedeputian_wilayah' => $user->kedeputian_wilayah]);
        }

        return $query->get();
    }

    public function getDistributionByBranch(array $filters = []): array
    {
        // If viewing national data (no regional filter), group by Kedeputian Wilayah (Regional) for better summary
        if (empty($filters['kedeputian_wilayah'])) {
            return \App\Models\KedeputianWilayah::withCount(['kantorCabangs as total' => function($q) {
                    $q->whereHas('members', function($mq) {
                        $mq->where('role', 'anggota'); // Pure Member Only
                    });
                }])
                ->get()
                ->map(function($item) {
                    // Correctly count total pure members across all branches in this region
                    $totalMembers = \App\Models\Member::where('role', 'anggota')
                        ->whereHas('kantorCabang', function($q) use ($item) {
                            $q->where('kedeputian_wilayah_id', $item->id);
                        })->count();

                    return [
                        'branch_name' => $item->name,
                        'total' => $totalMembers
                    ];
                })
                ->filter(fn($i) => $i['total'] > 0)
                ->values()
                ->toArray();
        }

        // If a specific region is selected, show all its branches (even those with 0 members)
        return \App\Models\KantorCabang::whereHas('kedeputianWilayah', function($q) use ($filters) {
                $q->where('name', $filters['kedeputian_wilayah']);
            })
            ->withCount(['members as total' => function($q) {
                $q->where('role', 'anggota'); // Pure Member Only
            }])
            ->get()
            ->map(function($item) {
                return [
                    'branch_name' => $item->name,
                    'total' => $item->total
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();
    }
}
