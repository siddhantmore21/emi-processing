<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LoanDetailsService;

class LoanDetailsController extends Controller
{
    protected $loanDetailsService;

    public function __construct(LoanDetailsService $loanDetailsService)
    {
        $this->loanDetailsService = $loanDetailsService;
    }

    public function index()
    {
        $loanDetails = $this->loanDetailsService->getAllLoanDetails();
        return view('admin.loan_details.index', compact('loanDetails'));
    }

}
