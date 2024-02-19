<?php
session_start();
// Set the inactivity timeout duration (in seconds)
$inactivityTimeout = 600;

// Check if the last activity timestamp exists in the session
if (isset($_SESSION['last_activity'])) {
    $currentTime = time();
    $lastActivity = $_SESSION['last_activity'];
    // Calculate the time difference in seconds
    $timeDifference = $currentTime - $lastActivity;

    // Check if the inactivity period has been exceeded
    if ($timeDifference > $inactivityTimeout) {
        // Destroy the session and redirect to index.php
        session_unset();
        session_destroy();

        // Redirect the user to index.php
        header('Location: https://voicesapp-oop-php-vanillajs-bs5.ddwebapps.com/index.php');
        exit; // Exit to prevent further execution of the script
    }
}

// Update the last activity timestamp in the session
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}
?>