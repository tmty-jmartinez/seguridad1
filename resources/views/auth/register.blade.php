@extends('layouts.app')

@section('title', 'User Registration')
@section('content')
<head>{!! NoCaptcha::renderJs() !!}</head>
    <h2>Register</h2>
    <form method="POST" action="/register">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <h5>
            The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.
        </h5>

        <!-- reCAPTCHA -->
        {!! NoCaptcha::display() !!}

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>

    <!-- Display validation errors -->
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: "{{ $errors->first() }}", // Display the first error
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
@endsection
