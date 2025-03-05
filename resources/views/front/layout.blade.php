<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sneakervault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

<!-- Navbar -->
<header>
    <!-- Desktop Navbar -->
    <nav id="desktop-nav">
        <div class="logo">SneakerVault</div>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('product.home') }}">Products</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
        </ul>
        <div class="d-flex align-items-center gap-3">
            @if(auth()->user())
                <i class="fa-solid fa-cart-shopping fa-xl position-relative" id="cart" onclick="redirectToCart()"
                   style="padding: 20px; cursor: pointer;">
                    <span id="cart-badge" class="badge bg-danger rounded-pill position-absolute"
                          style="top: -1px; right: -1px; font-size: 10px; padding: 4px 6px; display: none;"></span>
                </i>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-outline-dark" value="Logout">
                </form>
            @else
                <a href="{{ route('login') }}">
                    <button type="button" class="btn btn-outline-dark">Login</button>
                </a>
            @endif
        </div>
    </nav>

    <!-- Mobile/Hamburger Navbar -->
    <nav id="hamburger-nav" class="navbar px-3 px-md-5">
        <div class="logo">SneakerVault</div>
        <div class="d-flex align-items-center">
            @if(auth()->user())
                <i class="fa-solid fa-cart-shopping fa-xl position-relative" id="cart-mobile" onclick="redirectToCart()"
                   style="padding: 20px; cursor: pointer;">
                    <span id="cart-badge-mobile" class="badge bg-danger rounded-pill position-absolute"
                          style="top: -1px; right: -1px; font-size: 10px; padding: 4px 6px; display: none;"></span>
                </i>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-outline-dark" value="Logout">
                </form>
            @else
                <a href="{{ route('login') }}">
                    <button type="button" class="btn btn-outline-dark">Login</button>
                </a>
            @endif
        </div>
        <div class="hamburger-menu">
            <div class="hamburger-icon" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="menu-links">
                <li><a href="{{ route('product.home') }}" onclick="toggleMenu()">Products</a></li>
                <li><a href="{{ route('about') }}" onclick="toggleMenu()">About</a></li>
            </div>
        </div>
    </nav>
</header>

<!-- Content -->
@yield('content')

<!-- Footer -->
<footer class="footer">
    <div class="container px-5">
        <div class="row">
            <div class="col-md-6 text-center text-md-start ">
                <div class="logo">SneakerVault</div>
            </div>
            <div class="col-md-3 text-center text-md-start">
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
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script>
    AOS.init();

    function toggleMenu() {
        const menu = document.querySelector(".menu-links");
        const icon = document.querySelector(".hamburger-icon");
        menu.classList.toggle("open");
        icon.classList.toggle("open");
    }

    function redirectToCart() {
        let isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        window.location.href = isLoggedIn ? "/cart" : "/login";
    }

    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                console.log("Cart count received:", data.count);
                // Update desktop badge
                let desktopBadge = document.getElementById('cart-badge');
                if (desktopBadge) {
                    desktopBadge.innerText = data.count;
                    desktopBadge.style.display = data.count > 0 ? 'inline-block' : 'none';
                }
                // Update mobile badge
                let mobileBadge = document.getElementById('cart-badge-mobile');
                if (mobileBadge) {
                    mobileBadge.innerText = data.count;
                    mobileBadge.style.display = data.count > 0 ? 'inline-block' : 'none';
                }
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }

    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
</body>
</html>
