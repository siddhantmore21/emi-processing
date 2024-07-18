<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmiDetailsService;
use Illuminate\Support\MessageBag;

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
        $response = $this->emiDetailsService->processEmiCalculations($request->all());
        if($response['status'] == true)
        {
            return redirect()->route('emi-details.index')->with('success', $response['message']);
        }

        $errors = new MessageBag(['error' => $response['message']]);

        return redirect()->back()->withErrors($errors);
    }

}
