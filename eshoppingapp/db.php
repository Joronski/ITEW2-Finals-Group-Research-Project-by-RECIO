
<?php
    // Check if constants are already defined before including the file
    if (!defined('HOST')) {
        require "config/constants.php";
    }

    $servername = HOST;
    $username = USER;
    $password = PASSWORD;
    $db = DATABASE_NAME;
    $port = PORT;

    // Create connection
    $con = mysqli_connect($servername, $username, $password, $db, $port);

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>