@extends('layouts.app')

@section('content')
    
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h5>Error While Processing EMI Data</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                <h5>{{ session('success') }}</h5>
            </div>
        @endif
        <div class="d-flex justify-content-between mb-3">
            <h2>#EMI Details</h2>
            <form action="{{ route('process-emi-calculations') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Process Data</button>
            </form>
        </div>
        
        
        @if (isset($emiDetails) && $emiDetails )
            <div class="table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="table-dark">
                        <th class="text-nowrap">Client ID</th>
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
            </div>
        @else
            <h3>EMI Data is Not Generated. Click on Process Data Button to Generate Data.</h3>
        @endif
    </div>

@endsection
