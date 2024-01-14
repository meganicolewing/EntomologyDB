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
    $logged_in = false;
    if(isset($_SESSION['enthusiast_id'])){
        $logged_in = true;
    }
    $sampleid = -1;
    $result = $conn->query('SELECT sample_id FROM samples_view');
    for ($i=0; $i<$result->num_rows; $i++){
        if(isset($_GET['sample'.$i])){
            $sampleid = $result->fetch_all()[$i][0];
        }
    }
        $select_stmt = $conn->prepare("SELECT * FROM samples_view WHERE sample_id = ?");
        $select_stmt->bind_param('i',$sampleid);
        if($sampleid == -1 || !$select_stmt->execute()){
            ?> 
            <link rel = "stylesheet" href = "styles/stylish.css">
            <?php     $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
            <p>The sample you have requested is not in the archive. Please select a different sample.</p>
            <?php
        } 
        else{
            ?>
                    <link rel = "stylesheet" href = "styles/stylish.css">
    <?php     $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header);
    ?>
    <h1>Request This Sample?</h1>
    <?php
            $result = $select_stmt->get_result();
            result_to_formatted_table($result);
        if($logged_in){
            ?>
            <form action="requestMade.php" method=POST>
                <input type='hidden' name='sample' value='<?=$sampleid?>' />
                <input type='submit' name="request" value='Submit Request'/>
            </form>
            <?php
        }
        else{
            ?>
            <p>Please login to request a sample.</p>
            <?php
        }
    }
