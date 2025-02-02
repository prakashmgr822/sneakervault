<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sneakervault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<header>
    <nav id="desktop-nav">
        <div class="logo">SneakerVault</div>
        <!-- <div> -->
        <ul class="nav-links">
            <li><a href="#about">Home</a></li>
            <li><a href="#experience">Products</a></li>
            <li><a href="#projects">About</a></li>
        </ul>
        <!-- </div> -->
        <div>
            <button type="button" class="btn btn-outline-dark">Login</button>

        </div>
    </nav>
</header>
<!-- Hero -->
<section >
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-2 order-md-1"  data-aos="fade-up" data-aos-duration="3000">
                <div class="text-label">
                    All Sneaker in one place
                </div>
                <div class="text-hero-bold">
                    Passion for Sneaker
                </div>
                <div class="text-hero-regular">
                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                </div>
                <div class="cta flex-fill">
                    <a href="" class="btn btn-primary ">Explore Now</a>
                </div>
            </div>
            <div class="col-md-6 vh-83 order-1 order-md-2" id="hero_img" data-aos="fade-up" data-aos-duration="3000">
                <img src="img/hero.jpg" class="w-100 img-fluid" style=" object-fit: cover; height: 535.33px; max-height: 100%;"  alt="img">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="product">
    <div class="container mt-5 ">
        <div class="row justify-content-md-between">
            <div class="col-md-12">
                <div class="text-hero-bold text-center">
                    Featured Products
                </div>
            </div>
            <div class="col-md-4">
                <div class="card m-5">
                    <img src="img/nik" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Addidas ad-05</h5>
                        <a href="#" class="btn btn-primary w-100">Add to cart</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card m-5" >
                    <img src="img/nik" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Addidas ad-05</h5>
                        <a href="#" class="btn btn-primary w-100">Add to cart</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card m-5" >
                    <img src="img/nik" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Addidas ad-05</h5>
                        <a href="#" class="btn btn-primary w-100">Add to cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="row" >
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
<!-- AOS Animate -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    AOS.init();
</script>
</body>
</html>
