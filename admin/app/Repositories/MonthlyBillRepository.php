<?php

namespace App\Repositories;

use App\Models\MonthlyBill;
use Illuminate\Database\Eloquent\Collection;

class MonthlyBillRepository extends BaseRepository
{
    public function __construct(MonthlyBill $model)
    {
        parent::__construct($model);
    }

    public function getPending(): Collection
    {
        return $this->model->pending()->get();
    }

    public function getOverdue(): Collection
    {
        return $this->model->overdue()->get();
    }

    public function getByUnit(int $unitId): Collection
    {
        return $this->model->where('unit_id', $unitId)->get();
    }

    public function getByMonth(string $month): Collection
    {
        return $this->model->where('month', $month)->get();
    }
}

