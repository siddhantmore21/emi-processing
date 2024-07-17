<?php

namespace App\Services;

use App\Repositories\LoanDetailsRepository;

class LoanDetailsService
{
    protected $loanDetailsRepository;

    public function __construct(LoanDetailsRepository $loanDetailsRepository)
    {
        $this->loanDetailsRepository = $loanDetailsRepository;
    }

    public function getAllLoanDetails()
    {
        return $this->loanDetailsRepository->getAllLoanDetails();
    }

    public function getLoanDetailById($id)
    {
        return $this->loanDetailsRepository->getLoanDetailsById($id);
        
    }
}
