<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'result_to_table.php';
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
    $execution = false;
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
    else if(isset($_POST['insert'])){
        $good_insert = true;
        $execution = true;
        if(isset($_POST['Email']) && $_POST['Email'] != ''){
            $insert_stmt = $conn->prepare(file_get_contents('../SQL/insert_enthusiasts_2.sql'));
            $insert_stmt->bind_param('sssssssss',$_POST['FirstName'],$_POST['LastName'],$_POST['Address'],$_POST['City'],$_POST['Region'],$_POST['Postal'],$_POST['Country'],$_POST['Email'],$_POST['Phone']);
            if(!$insert_stmt->execute()){
                $good_insert = false;
                ?>
                <h3>Addition failed. There may already be an enthusiast with that email.</h3>
                <?php
            }
        }
        else{
            $good_insert = false;
            ?>
            <h3>Addition failed. Remember that an email is required.</h3>
            <?php
        }
        if($good_insert){
            header("Location: {$_SERVER['REQUEST_URI']}", TRUE, 303);
            exit();
        }
    }
    else{
        ?>
        <h2>Add an Enthusiast</h2>
        <?php
        $result = $conn->query("SELECT enthusiast_first_name as 'FirstName', enthusiast_last_name as 'LastName', enthusiast_street_address as 'Address', enthusiast_city as 'City', enthusiast_region as 'Region', enthusiast_zipcode as 'Postal', enthusiast_country as 'Country', enthusiast_email_address as 'Email', enthusiast_phone_number as 'Phone' FROM enthusiasts;");
        result_to_insert_form($result,'addEnthusiast.php');
    }
    ?>