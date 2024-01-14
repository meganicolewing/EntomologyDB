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
        if(isset($_POST['Binomen']) && isset($_POST['Taxon']) && $_POST['Binomen'] != '' && $_POST['Taxon']!=''){
            $insert_stmt = $conn->prepare(file_get_contents('../SQL/insert_samples.sql'));
            $insert_stmt->bind_param('sssss',$_POST['Binomen'],$_POST['Taxon'],$_POST['Length'],$_POST['North'],$_POST['West']);
            if(!$insert_stmt->execute()){
                $good_insert = false;
                ?>
                <h3>Addition failed.</h3>
                <?php
            }
        }
        else{
            $good_insert = false;
            ?>
            <h3>Addition failed. It looks like you didn't provide enough information.</h3>
            <?php
        }
        if($good_insert){
            header("Location: {$_SERVER['REQUEST_URI']}", TRUE, 303);
            exit();
        }
    }
    else{
        ?>
        <h2>Add a Sample</h2>
        <?php
        $result = $conn->query("SELECT species_binomial_name as 'Binomen', classifications_taxon as 'Taxon', sample_length as 'Length', sample_north_coordinate as 'North', sample_west_coordinate as 'West' FROM samples;");
        result_to_insert_form($result,'addSample.php');
    }
    ?>