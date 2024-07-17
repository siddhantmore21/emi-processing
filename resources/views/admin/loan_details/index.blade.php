    <div class="container">
        <h1>Loan Details</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
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
                        <td>{{ $loan->client_id }}</td>
                        <td>{{ $loan->num_of_payment }}</td>
                        <td>{{ $loan->first_payment_date }}</td>
                        <td>{{ $loan->last_payment_date }}</td>
                        <td>{{ $loan->loan_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
