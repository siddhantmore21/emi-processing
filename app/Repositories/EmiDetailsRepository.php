<?php

namespace App\Repositories;

use App\Models\EmiDetails;
use App\Models\LoanDetails;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmiDetailsRepository
{
    protected $model;

    public function __construct(EmiDetails $model)
    {
        $this->model = $model;
    }

    public function getAllEmiDetails()
    {
        return $this->model->all();
    }

    public function emiDetailsTableExists()
    {
        return DB::select("SHOW TABLES LIKE 'emi_details'");

    }

    private function getMonths($minDate,$maxDate)
    {
        $columns = [];
        $currentDate = Carbon::parse($minDate);

        while($currentDate <= Carbon::parse($maxDate))
        {
            $columns[] = $currentDate->format('Y_M');
            $currentDate->addMonth();
        }

        return $columns;
    }

    public function createEmiDetailsTable()
    {
        $minMaxDates = $this->getGloalMinMaxDates();
        $minDate = $minMaxDates['minDate'];
        $maxDate = $minMaxDates['maxDate'];

        $columns = $this->getMonths($minDate, $maxDate);


        $query = 'CREATE TABLE emi_details (
            client_id INT NOT NULL,' . implode(' DECIMAL(10, 2) DEFAULT 0.00, ', $columns) . ' DECIMAL(10, 2) DEFAULT 0.00
        )';

        Log::info('EMI TABLE CREATE QUERY :: '.json_encode($query));


        DB::statement($query);

    }

    public function dropEmiDetailsTable()
    {
        DB::statement('DROP TABLE IF EXISTS emi_details');
    }

    private function getGloalMinMaxDates()
    {
        $minMaxDates = LoanDetails::selectRaw('MIN(first_payment_date) as min_date, MAX(last_payment_date) as max_date')
        ->first();

        $minDate = $minMaxDates->min_date;
        $maxDate = $minMaxDates->max_date;

        return ['minDate' => $minDate, 'maxDate' => $maxDate];
    }
    public function processEmiData()
    {
        $loanDetails = LoanDetails::all();

        $minMaxDates = $this->getGloalMinMaxDates();
        $minDate = $minMaxDates['minDate'];
        $maxDate = $minMaxDates['maxDate'];
        $globalMonths = $this->getMonths($minDate,$maxDate);

        $query = 'INSERT INTO emi_details (client_id, ' . implode(', ', $globalMonths) . ')';

        $query .= ' VALUES ';
        
        foreach ($loanDetails as $i => $loan) {
            $emiAmount = $loan->loan_amount / $loan->num_of_payment;
            $lastMonth = Carbon::parse($loan->last_month)->format('Y-m');
            $emiMonths = $this->getMonths($loan->first_payment_date,$loan->last_payment_date);

            $query .= '('.$loan->client_id.',';

            $totalEmi = 0.0;

            foreach ($globalMonths as $index => $month) {
                if(in_array($month,$emiMonths))
                {
                    $emi = $month == $lastMonth ? $loan->loan_amount - $totalEmi : $emiAmount;
                    $emi = round($emi,2);
                    $totalEmi += $emi;
                }
                else
                {
                    $emi = 0.00;   
                }
                $query .= $index == (count($globalMonths) - 1) ? $emi.' ' : $emi.', ';
            }

            $query .= $i == ($loanDetails->count() - 1) ? ');' : '),';
        }

        Log::info('EMI TABLE INSERT DATA QUERY :: '.json_encode($query));

        DB::insert($query);

        
    }

    public function getEmiDetailsById($id)
    {
        return $this->model->findOrFail($id);
    }
}
