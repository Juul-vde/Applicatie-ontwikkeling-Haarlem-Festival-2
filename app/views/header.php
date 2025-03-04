<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" type="image/png" href="/media/favicon.png">

</head>

<body>
    <header class="p-3 text-white">
        <div class="container-fluid">
            <div class="logo">
                <a href="/">
                    <img src="/media/logo.png" alt="Logo">
                </a>
            </div>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="nav-link px-2 text-white">HOME</a></li>
                <li><a href="/dance" class="nav-link px-2 text-white">DANCE!</a></li>
                <li><a href="/food" class="nav-link px-2 text-white">FOOD</a></li>
                <li><a href="/history" class="nav-link px-2 text-white">HISTORY</a></li>
                <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true): ?>
                    <li><a href="/user/dashboard" class="nav-link px-2 text-white">DASHBOARD</a></li>
                <?php endif; ?>
            </ul>

            <div class="text-end">
                <?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false): ?>
                    <!-- Display Login and Sign-up buttons if the user is not logged in -->
                    <a href="/user/login" class="btn btn-outline-light me-2">Login</a>
                    <a href="/user/register" class="btn btn-signup me-2">Sign-up</a>
                <?php else: ?>
                    <!-- Display logout button if the user is logged in -->
                    <a href="/user/logout" class="btn btn-outline-light me-2">Logout</a>
                <?php endif; ?>
            </div>

            <div class="cart">
                <a href="/">
                    <img src="/media/shoppingcart.png" alt="Shopping Cart">
                </a>
            </div>
        </div>
    </header>
    <main>

    </main>
</body>

</html>