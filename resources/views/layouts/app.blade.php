<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>@yield('Login Jatz', 'Login Jatz')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f4e3; /* Creamy white */
            color: #4b5320; /* Military green */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        header {
            background-color: #4b5320;
            color: #f8f4e3;
            padding: 10px;
            text-align: center;
            width: 100%;
        }
        main {
            padding: 20px;
            width: 100%;
            max-width: 60%;
            min-width: 30%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
            margin-top: 5%;
            margin-bottom: 5%;
        }
        button {
            background-color: #4b5320;
            color: #f8f4e3;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #3e4a1b;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        a {
            color: #4b5320;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>@yield('header', 'Login2FA Jatz')</h1>
    </header>
    <main>
        @yield('content')
    </main>

    <!-- Show SweetAlert2 only if there are session messages -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: "{{ $errors->first() }}",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

</body>
</html>
