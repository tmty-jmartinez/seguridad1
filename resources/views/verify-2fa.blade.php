@extends('layouts.app')

@section('title', '2FA Verification')

@section('content')
    <h2>Verify Your Code</h2>
    
    <form method="POST" action="{{ route('verify-2fa') }}">
        @csrf

        <label for="two_factor_code">Verification Code:</label>
        <input type="text" name="two_factor_code" placeholder="Enter the code" required>

        <!-- reCAPTCHA -->
        {!! NoCaptcha::display() !!}

        <button type="submit">Verify</button>
    </form>

    {!! NoCaptcha::renderJs() !!}
    
    <p><a href="{{ route('login') }}">Back to Login</a></p>
@endsection
