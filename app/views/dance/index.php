<?php
// Start the session before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../header.php'; ?>

<body>
    <div class="image-container">
        <img src="/media/dancepagebanner.png" class="history-main-banner" alt="DANCE">
        <div class="overlay-text-dance">
            <h1 class="main-title">DANCE</h1>
            <h3 class="subtitle">Experience the music in Haarlem</h3>
            <a href="/" class="cta-button">GET TICKETS</a>
        </div>
    </div>
    </main>
    <?php include_once __DIR__ . '/../footer.php'; ?>
</body>