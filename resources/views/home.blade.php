@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h2>Welcome</h2>
    @if (Auth::check())
        <p>Hello, {{ Auth::user()->name }}.</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @else
        <p>You are not authenticated. <a href="{{ route('login') }}">Login</a></p>
    @endif
@endsection

