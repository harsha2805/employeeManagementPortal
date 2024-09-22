@php
    use App\Helpers\GetUserNameAndRole;

    $userDetails = GetUserNameAndRole::getUserDetails(session('user_id'));
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }

        .navbar {
            background-color: #333;
            color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-links p,
        .nav-links small {
            color: #fff;
            margin: 0 15px 0 0;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #555;
            margin-left: 10px;
        }

        .nav-links a:hover {
            background-color: #777;
        }

        .container {
            margin: 20px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            margin: 0;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .card p {
            margin: 0;
            font-size: 1rem;
        }

        .employee-list {
            list-style: none;
            padding: 0;
        }

        .employee-list li {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        @media screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links a {
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">
            <h2>Employee Management Portal</h2>
        </div>
        <div class="nav-links">
            <p>{{ $userDetails['name'] }}</p>
        </div>
        <div class="nav-links">
            <p>{{ $userDetails['role'] }}</p>
            <p>{{ $userDetails['location'] ?? '' }}</p>
            <a href="{{ route('logout') }}">Logout</a>
        </div>
    </div>

    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    @stack('customScripts')
</body>

</html>
