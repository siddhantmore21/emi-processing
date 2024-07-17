<?php

namespace App\Repositories;

use App\Models\LoanDetails;

class LoanDetailsRepository
{
    protected $model;

    public function __construct(LoanDetails $model)
    {
        $this->model = $model;
    }

    public function getAllLoanDetails()
    {
        return $this->model->all();
    }

    public function getLoanDetailsById($id)
    {
        return $this->model->findOrFail($id);
    }
}
