<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sneakervault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
    @yield('styles')
</head>
<body>

<!-- Navbar -->
<header>



    <nav id="desktop-nav ">
        <div class="logo">SneakerVault</div>
        <!-- <div> -->
        <ul class="nav-links">
            <li><a href="{{route('home')}}">Home</a></li>
            <li><a href="{{route('product.home')}}">Products</a></li>
            <li><a href="#projects">About</a></li>
        </ul>
        <!-- </div> -->
        <div>
            <i class="fa-solid fa-cart-shopping fa-xl"  style="padding: 20px"></i>
            @if(auth()->user())
                <form id="logout-form" action="{{route('logout')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-outline-dark" value="Logout">
                </form>
            @else
                <a href="{{route('login')}}">
                    <button type="button" class="btn btn-outline-dark">Login</button>
                </a>
            @endif
        </div>
    </nav>
</header>
<!-- Hero -->
@yield('content')

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start p-2">
                <div class="logo">SneakerVault</div>
            </div>
            <div class="col-md-3 text-center text-md-start p-2">
                <div class="text-label-medium">About us</div>
                <div class="text-label-regular">Our Products</div>
                <div class="text-label-regular">Contact Us</div>
            </div>
            <div class="col-md-3 text-center text-md-start">
                <div class="text-label-medium">Follow</div>
                <div class="text-label-regular">Instagram</div>
                <div class="text-label-regular">Facebook</div>
            </div>
        </div>
        <div class="row align-items-end" style="padding-top: 60px;">
            <div class="col-md-12 text-center">
                &copy;2025 SneakerVault all right reserved!
            </div>
        </div>
    </div>
</footer>

@yield('scripts')
<!-- AOS Animate -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script>
    AOS.init();
</script>
</body>
</html>
