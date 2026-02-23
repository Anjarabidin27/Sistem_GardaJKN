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
            ->with(['province', 'city', 'district']);

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

        if (!empty($filters['only_deleted']) && $filters['only_deleted'] === 'true') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        return $query->latest()->paginate($perPage);
    }

    public function countByMonth(int $limit = 6): array
    {
        return $this->model->selectRaw("TO_CHAR(created_at, 'Mon YYYY') as month, count(*) as total, MIN(created_at) as sort_date")
            ->groupBy(\DB::raw("TO_CHAR(created_at, 'Mon YYYY')"))
            ->orderBy('sort_date', 'DESC')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }

    public function getDistribution(string $column): array
    {
        return $this->model->select($column, \DB::raw('count(*) as total'))
            ->groupBy($column)
            ->get()
            ->toArray();
    }

    public function countActiveProvinces(): int
    {
        return $this->model->distinct('province_id')->count('province_id');
    }
}
