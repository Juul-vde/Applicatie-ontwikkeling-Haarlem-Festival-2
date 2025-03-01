<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../header.php';
    echo '<p>History</p>';

include __DIR__ . '/../footer.php';
?>