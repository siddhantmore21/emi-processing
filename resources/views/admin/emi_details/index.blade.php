    <div class="container">
        <h1>EMI Details</h1>
        <form action="{{ route('process-emi-calculations') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Process Data</button>
        </form>
        
        {{-- Display EMI Details table --}}
        @if (isset($emiDetails) && $emiDetails )
            <h2>EMI Details</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Client ID</th>
                        @foreach ($months as $month)
                            <th>{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emiDetails as $detail)
                        <tr>
                            <td>{{ $detail->client_id ?? 'NA' }}</td>
                            @foreach ($months as $month)
                                <td>{{ isset($detail->{$month}) ? number_format($detail->{$month}, 2) : '0.00' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
