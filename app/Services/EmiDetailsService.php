<?php

namespace App\Services;

use App\Repositories\EmiDetailsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Request;

class EmiDetailsService
{
    protected $emiDetailsRepository;

    public function __construct(EmiDetailsRepository $emiDetailsRepository)
    {
        $this->emiDetailsRepository = $emiDetailsRepository;
    }

    public function getAllEmiDetails()
    {
        return $this->emiDetailsRepository->getAllEmiDetails();
    }

    public function emiDetailsTableExists()
    {
        return $this->emiDetailsRepository->emiDetailsTableExists();
    }

    private function createEmiDetailsTable()
    {
        $this->emiDetailsRepository->createEmiDetailsTable();
    }

    private function dropEmiDetailsTable()
    {
        $this->emiDetailsRepository->dropEmiDetailsTable();
    }

    private function generateEmiDetailsTable()
    {
        $emiDetailsTableExists = $this->emiDetailsTableExists();


        if (empty($emiDetailsTableExists)) {
            $this->createEmiDetailsTable();
        } else {
            Log::info('EMI TABLE EXISTS');
            $this->dropEmiDetailsTable();
            Log::info('EMI TABLE DROPPED');
            $this->createEmiDetailsTable();
            Log::info('NEW EMI TABLE CREATED');
        }
    }

    private function processEmiData()
    {
        $this->emiDetailsRepository->processEmiData();
    }

    public function processEmiCalculations($postData=[])
    {
        Log::info('PROCESSING EMI CALCULATIONS...');
        DB::beginTransaction();
        try
        {
            $this->generateEmiDetailsTable();
            Log::info('EMI TABLE GENERATED');

            $this->processEmiData();
            Log::info('EMI DATA PROCESSED SUCCESSFULLY');

            DB::commit();
        }
        catch(\Exception $e)
        {
            Log::error('EMI DATA PROCESSING FAILED DUE TO '.json_encode($e->getMessage()). ' ON LINE '.json_encode($e->getLine()));

            DB::rollBack(); 
        }

    }





    public function getEmiDetailById($id)
    {
        return $this->emiDetailsRepository->getEmiDetailsById($id);
        
    }
}