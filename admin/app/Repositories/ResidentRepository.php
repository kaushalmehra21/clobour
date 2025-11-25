<?php

namespace App\Repositories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Collection;

class ResidentRepository extends BaseRepository
{
    public function __construct(Resident $model)
    {
        parent::__construct($model);
    }

    public function getByUnit(int $unitId): Collection
    {
        return $this->model->where('unit_id', $unitId)->get();
    }

    public function getActive(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }

    public function getWithUnit()
    {
        return $this->model->with('unit')->get();
    }
}

