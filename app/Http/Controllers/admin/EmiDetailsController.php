<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmiDetailsService;

class EmiDetailsController extends Controller
{
    protected $emiDetailsService;

    public function __construct(EmiDetailsService $emiDetailsService)
    {
        $this->emiDetailsService = $emiDetailsService;
    }

    public function index()
    {
        $emiDetailsExists = $this->emiDetailsService->emiDetailsTableExists();
        $emiDetails = $emiDetailsExists ? $this->emiDetailsService->getAllEmiDetails() : [];
        $months = $emiDetailsExists ? $this->emiDetailsService->getMonths() : [];
        return view('admin.emi_details.index', compact('emiDetails','months'));
    }

    public function processEmiCalculations(Request $request)
    {
        $this->emiDetailsService->processEmiCalculations($request->all());
        redirect()->route('emi-details.index');
    }

}
