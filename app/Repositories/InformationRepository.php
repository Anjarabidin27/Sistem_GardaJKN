<?php

namespace App\Repositories;

use App\Models\Information;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InformationRepository extends BaseRepository
{
    public function __construct(Information $model)
    {
        parent::__construct($model);
    }

    public function getFilteredList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where('title', 'LIKE', '%' . $filters['search'] . '%');
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getActiveInformations(): Collection
    {
        return $this->model->where('is_active', true)->latest()->get();
    }
}
