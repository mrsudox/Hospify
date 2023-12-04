<?php 
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// Set the session timeout period in seconds (60 seconds in this example)
$sessionTimeout = 600;

// Check if the session variable for the last activity time is set
if (isset($_SESSION['last_activity'])) {
    // Calculate the time difference between now and the last activity time
    $inactiveTime = time() - $_SESSION['last_activity'];

    // Check if the inactive time exceeds the session timeout
    if ($inactiveTime >= $sessionTimeout) {
        // Destroy the session and redirect to a login page or perform other actions as needed
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Update the last activity time in the session
$_SESSION['last_activity'] = time();


include "db.php";
include 'functions.php';
include 'settings_constant.php';


?>