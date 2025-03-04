<?php
// Start the session before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../header.php'; ?>

<body>
    <div class="image-container">
        <img src="/media/historymainbanner.png" class="history-main-banner" alt="St. Bavo Church">
        <div class="overlay-text-history">
            <h1 class="main-title">HISTORY TOURS</h1>
            <h3 class="subtitle">Discover Haarlem's Rich History</h3>
            <p class="description">Join our expert guides on a journey through time as we explore the magnificent historical landmarks of Haarlem. Experience centuries of Dutch history, culture and architecture.</p>
            <a href="/" class="cta-button">View Tours</a>
        </div>
    </div>
    </main>
    <div class="featured-locations-slider">
  <!-- Slider Navigation Buttons -->
  <button class="slider-btn prev-btn">←</button>
  
  <!-- Slider Cards Container -->
  <div class="slider-container">
    <!-- Each location card inside the slider -->
    <div class="location-slide">
      <div class="card">
        <div class="card-image-box">
          <img src="images/location1.jpg" alt="Location 1" class="card-image">
        </div>
        <div class="card-text">
          <h2>Location 1</h2>
          <p>Description of location 1 goes here.</p>
        </div>
      </div>
    </div>

    <div class="location-slide">
      <div class="card">
        <div class="card-image-box">
          <img src="images/location2.jpg" alt="Location 2" class="card-image">
        </div>
        <div class="card-text">
          <h2>Location 2</h2>
          <p>Description of location 2 goes here.</p>
        </div>
      </div>
    </div>

    <!-- More location slides can be added here -->
  </div>

  <!-- Slider Navigation Buttons -->
  <button class="slider-btn next-btn">→</button>
</div>


    <script>
        // JavaScript for the slider functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.location-slide');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        // Initial display
        showSlide(currentSlide);

        // Optional: Auto-slide every 5 seconds
        setInterval(nextSlide, 5000);
    </script>

    <?php include_once __DIR__ . '/../footer.php'; ?>
</body>