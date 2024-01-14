<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
        exit;
    };
    session_start();
        ?>
        <link rel = "stylesheet" href = "styles/stylish.css">
        <?php 
            $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header);
    if(!isset($_SESSION['archive'])){
        ?>
        <p><b>Only archive associates can update data. If you are an associate, please login.</b></p>
        <?php
    }
    else{
        ?>
        <h1>Click a Link to Update Info</h1>
        <table><tbody>
            <tr><td><h2> Species:</h2></td>
                <td><a href = 'addSpecies.php'>Add a Species</a></td>
                <td><a href = 'updateSpecies.php'>Update a Species</a></td>
                <td><a href = 'removeSpecies.php'>Remove a Species</a></td>
            </tr>
            <tr><td><h2> Samples:</h2></td>
                <td><a href = 'addSample.php'>Add a Sample</a></td>
                <td><a href = 'updateSample.php'>Update a Sample</a></td>
                <td><a href = 'removeSample.php'>Remove a Sample</a></td>
            </tr>
            <tr><td><h2> Enthusiasts:</h2></td>
                <td><a href = 'addEnthusiast.php'>Add an Enthusiast</a></td>
                <td><a href = 'updateEnthusiast.php'>Update an Enthusiast</a></td>
                <td><a href = 'removeEnthusiast.php'>Remove an Enthusiast</a></td>
            </tr>
            <tr><td><h2> Requests:</h2></td>
                <td><a href = 'addRequest.php'>Add a Request</a></td>
                <td><a href = 'updateRequest.php'>Update a Request</a></td>
                <td><a href = 'removeRequest.php'>Remove a Request</a></td>
            </tr>
        </tbody></table
    <?php
    }
    ?>