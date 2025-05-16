<?php
    session_start();

    if (isset($_SESSION["admin_id"])) {
        // Store session variables temporarily
        $admin_id = $_SESSION["admin_id"];
        $admin_name = $_SESSION["admin_name"];
        $admin_email = $_SESSION["admin_email"];

        // Clear all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        // Start a new session for the logout message
        session_start();

        // Set logout message
        $_SESSION['logout_message'] = "You have been successfully logged out!";

        // Redirect to login page
        header("location:login.php");
        exit();
    } else {
        // If not logged in, redirect to login page
        header("location:login.php");
        exit();
    }
?>