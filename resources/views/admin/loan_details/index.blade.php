@extends('layouts.app')

@section('content')

   <div class="container">
        <div class="d-flex justify-content-between mb-3">
         <h2>#Loan Details</h2>
        </div>
        <div class="table-responsive">

        <table class="table table-bordered  table-striped">
            <thead>
                <tr class="table-dark">
                    <th>Client ID</th>
                    <th>Number of Payments</th>
                    <th>First Payment Date</th>
                    <th>Last Payment Date</th>
                    <th>Loan Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loanDetails as $loan)
                    <tr>
                        <td>{{ $loan->client_id ?? '-' }}</td>
                        <td>{{ $loan->num_of_payment ?? '0' }}</td>
                        <td>{{ $loan->first_payment_date ?? 'NA' }}</td>
                        <td>{{ $loan->last_payment_date ?? 'NA' }}</td>
                        <td>{{ $loan->loan_amount ?? '0' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

@endsection
