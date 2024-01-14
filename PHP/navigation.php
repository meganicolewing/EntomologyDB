<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $config = parse_ini_file('../../mysqli.ini');
      $dbname = 'instrument_rentals';
      $conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname);

    if ($conn->connect_errno) {
        echo "Error: Failed to make a MySQL connection, here is why: ". "<br>";
        echo "Errno: " . $conn->connect_errno . "\n";
        echo "Error: " . $conn->connect_error . "\n";
        exit;
    };
    session_start();
?>
<html>
    <link rel = "stylesheet" href = "styles/stylish.css">
    <?php    $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
        </form>
    <h1>Welcome to the Mid-America Entymological Archive! </h1>
    
        <img class = "headerIMG" src="images/grass.jpg">
        
    <h2><b>About us: </b></h2>

    <p>Our aim is to create a database of all of our samples and species for anyone interested or enthused about bugs. We also will send orders to other researchers that request 
        a specific bug sample from our archive. </p>
    
    <h2><b>Our mission: </b></h2>
    <p>The purpose of the Mid-America Entomological database is to maintain and provide information about entomological species for individuals such as researchers, landowners, 
        and outdoor enthusiasts. We also aim to allow researchers to request specimen samples.</p>
</html>