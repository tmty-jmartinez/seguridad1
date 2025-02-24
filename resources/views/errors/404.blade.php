@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="container">
        <h2>Oops! The page you are looking for does not exist.</h2>
        <p>You can go back to the login page by clicking the button below.</p>
        <form action="{{ route('login') }}" method="get">
            <button type="submit" class="btn btn-primary">Go to Login</button>
        </form>
    </div>
@endsection

@section('styles')
    <style>
        .container {
            text-align: center;
            margin-top: 50px;
        }
        h2 {
            color: #4b5320;
        }
        .btn-primary {
            background-color: #4b5320;
            color: #f8f4e3;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #3e4a1b;
        }
    </style>
@endsection
