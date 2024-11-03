<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - BricksPoint E-Catalog</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    
    <style>
        .carousel-inner { height: 280px; }
        .carousel-inner img { height: 100%; width: auto; object-fit: cover; }
        
        .status-text {
            font-family: "Georgia", serif;
            font-size: 1rem;
        }
        .status-available {
            color: #28a745;
            font-weight: bold;
            background: linear-gradient(120deg, #e0ffe8, #d4ffdf);
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .status-unavailable {
            color: #b35b5b;
            font-weight: 600;
            background: linear-gradient(120deg, #ffe6e6, #ffd9d9);
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .feedback:hover:after{
            content:" Coming soon...";
            font-family: Gotham;
            font-style: italic;
        }
    </style>
</head>
<body>

<header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="{{route('rooms.index')}}" class="navbar-brand d-flex align-items-center">
                <strong>BRICKSPOINT</strong>&nbsp;
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     class="me-2" viewBox="0 0 24 24">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                    <circle cx="12" cy="13" r="4" />
                </svg>
                <strong>E-Catalog</strong>
            </a>
        </div>
        @if (auth()->check())
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    @endif
    
        
        
    </div>
</header>

<main class="container my-5">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
    // Initialize Fancybox
    Fancybox.bind("[data-fancybox='gallery']", {
        closeButton: "outside",
        animated: true,
        dragToClose: true,
    });
</script>

</body>
</html>