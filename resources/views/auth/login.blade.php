@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>
    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <!-- reCAPTCHA -->
        {!! NoCaptcha::display() !!}
        
        <button type="submit">Log In</button>
    </form>

    {!! NoCaptcha::renderJs() !!}
    
    <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
@endsection

