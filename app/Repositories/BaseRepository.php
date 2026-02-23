<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id, array $relations = []): ?Model
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->findById($id);
        return $model->update($data);
    }

    public function delete(int $id): bool
    {
        $model = $this->findById($id);
        return $model->delete();
    }

    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->latest()->paginate($perPage);
    }

    public function countAll(): int
    {
        return $this->model->count();
    }
}
