<?php

namespace App\Repositories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

class UnitRepository extends BaseRepository
{
    public function __construct(Unit $model)
    {
        parent::__construct($model);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function getByBlock(string $block): Collection
    {
        return $this->model->where('block', $block)->get();
    }

    public function getWithResidents()
    {
        return $this->model->with('activeResident')->get();
    }
}

