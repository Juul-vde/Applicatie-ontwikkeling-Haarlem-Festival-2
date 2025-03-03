<?php
// Start the session before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../header.php';


// if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
//     echo '<p>Welcome, you are logged in!</p>';
// } else {
//     echo '<p>You are not logged in.</p>';
// }
?>

<body>
    <main class="main-content">
        <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/media/dancemainbanner.png" class="d-block w-100" alt="Martin Garrix">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1>Martin Garrix</h1>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/media/foodmainbanner.png" class="d-block w-100" alt="Food">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1>Food</h1>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/media/historymainbanner.png" class="d-block w-100" alt="St. Bavo Church">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1>Church of St. Bavo</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="cards-container">
            <div class="card-container-flex">
                <div class="card-image-box">
                    <img src="/media/dancecard.png" alt="Dance Image" class="card-image">
                </div>
                <div class="info-card">
                    <div class="card-text">
                        <h2>DANCE!</h2>
                        <p>Feel the rhythm of Haarlem!</p>
                        <p>Immerse yourself in electrifying performances and vibrant dance events. Experience an exciting fusion of styles, from traditional to contemporary, as the city comes alive with movement and music.</p>
                        <a href="#" class="cta-button">VIEW TICKETS</a>
                    </div>
                </div>
            </div>

            <div class="card-container-flex">
                <div class="info-card">
                    <div class="card-text">
                        <h2>HISTORY TOURS</h2>
                        <p>Discover Haarlem’s rich history</p>
                        <p>Join our expert guides on a journey through time as we explore the magnificent historical landmarks of Haarlem. Experience centuries of Dutch history, culture, and architecture.</p>
                        <a href="#" class="cta-button">VIEW TOURS</a>
                    </div>
                </div>
                <div class="card-image-box">
                    <img src="/media/historycard.png" alt="History Image" class="card-image">
                </div>
            </div>

            <div class="card-container-flex">
                <div class="card-image-box">
                    <img src="/media/foodcard.png" alt="Food Image" class="card-image">
                </div>
                <div class="info-card">
                    <div class="card-text">
                        <h2>FOOD</h2>
                        <p>Savor the flavors of Haarlem!</p>
                        <p>Embark on a culinary adventure through the city's finest restaurants and food spots. Taste a variety of delicious dishes that showcase Haarlem’s rich gastronomic heritage.</p>
                        <a href="#" class="cta-button">LEARN MORE</a>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <?php include_once __DIR__ . '/../footer.php'; ?>
</body>