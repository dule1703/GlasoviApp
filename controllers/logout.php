<?php
session_start();
function logout() {
    // Clear all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect the user to the login page or any other desired page
    header("Location: ../index.php");
    exit; // Stop further execution of the script
}

// Call the logout function
logout();