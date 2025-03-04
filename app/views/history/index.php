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
    <?php include_once __DIR__ . '/../footer.php'; ?>
</body>