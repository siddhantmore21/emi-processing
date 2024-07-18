@extends('layouts.app')

@section('content')

    <div class="container d-flex justify-content-center align-items-center mt-5">
        <div class="text-center">
            @auth
            <h1>Hi {{ Auth::user()->name }},</h1>

            @endauth

            <h1>Welcome to EMI Processing</h1>
            @guest
                <br>
                <h4>Please Login or Register</h4>
            @endguest
        </div>
    </div>

@endsection