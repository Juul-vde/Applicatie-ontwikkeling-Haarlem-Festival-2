<?php
// Start the session before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../header.php';


if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    echo '<p>Welcome, you are logged in!</p>';
} else {
    echo '<p>You are not logged in.</p>';
}

include __DIR__ . '/../footer.php';
?>