<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require 'result_to_table.php';
    $config = parse_ini_file('../../mysqli.ini');
    $dbname = 'entomological_archive';
    $conn = new mysqli(
            $config['mysqli.default_host'],
            $config['mysqli.default_user'],
            $config['mysqli.default_pw'],
            $dbname);
    if ($conn->connect_errno) {
        echo "Error: Failed to make a MySQL connection, here is why: ". "<br>";
        echo "Errno: " . $conn->connect_errno . "\n";
        echo "Error: " . $conn->connect_error . "\n";
        exit; // Quit this PHP script if the connection fails.
    }
    session_start();
?>
<html>
    <link rel = "stylesheet" href = "styles/stylish.css">
    <?php 
        $header = '../PHP/styles/header.html';
        if(isset($_SESSION['archive'])){
            $header = '../PHP/styles/archive_header.html';
        }
        echo file_get_contents($header)?>
    <h1>Available Samples</h1>
<?php

    $result = $conn->query('SELECT * FROM samples_view');
    result_to_clickable_table($result);
?>
</html>