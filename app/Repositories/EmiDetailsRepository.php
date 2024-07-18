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

    public function getMonths($minDate,$maxDate)
    {
        $columns = [];
        $currentDate = Carbon::parse($minDate)->startOfMonth();

        while($currentDate <= Carbon::parse($maxDate)->startOfMonth())
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

    public function getGloalMinMaxDates()
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

        Log::info('EMI TABLE INSERT GLOBAL MONTHS :: '.json_encode($globalMonths));

        $query = 'INSERT INTO emi_details (client_id, ' . implode(', ', $globalMonths) . ')';

        $query .= ' VALUES ';
        
        foreach ($loanDetails as $i => $loan) {
            $emiAmount = $loan->loan_amount / $loan->num_of_payment;

            $emiMonths = $this->getMonths($loan->first_payment_date,$loan->last_payment_date);
            Log::info('CLIENT ID :: '.json_encode($loan->id).' EMI TABLE INSERT EMI MONTHS :: '.json_encode($emiMonths));

            $lastMonth = end($emiMonths);
            Log::info('CLIENT ID :: '.json_encode($loan->id).' LAST MONTH :: '.json_encode($lastMonth));


           
            $query .= '('.$loan->client_id.',';

            $totalEmi = 0.0;

            foreach ($globalMonths as $index => $month) {
                Log::info('EMI MONTH :: '.json_encode($month));

                Log::info('CLIENT ID :: '.json_encode($loan->id).' TOTAL EMI TILL NOW :: '.json_encode($totalEmi).' EMI MONTHLY :: '.json_encode($emiAmount).' LOAN AMOUNT :: '.json_encode($loan->loan_amount));

                $emi = 0.00;

                if(in_array($month,$emiMonths))
                {
                    $emi = $month == $lastMonth ? $loan->loan_amount - $totalEmi : $emiAmount;
                    $emi = round($emi,2);
                    $totalEmi += $emi;

                }

                Log::info('CLIENT ID :: '.json_encode($loan->id).' TOTAL EMI TILL NOW :: '.json_encode($totalEmi).' NEW EMI MONTHLY :: '.json_encode($emi));

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
