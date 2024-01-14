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
    if(!isset($_SESSION['enthusiast_id'])){
        ?>
        <link rel = "stylesheet" href = "styles/stylish.css">
        <?php echo file_get_contents('../PHP/styles/header.html')?>
        <p>Please login to request a sample.</p>
        <a href='login.php'>Login Here</p>
        <?php

    }
    else if(isset($_POST['sample'])){
        $enthusiast_id = $_SESSION['enthusiast_id'];
        $sample_id = -1;
        $result = $conn->query('SELECT sample_id FROM samples_view');
        $data = $result->fetch_all();
        for ($i = 0; $i<$result->num_rows; $i++){
            $id = $data[$i][0];
            if($_POST['sample'] == $id){
                $sample_id = $id;
            }
        }
        $request_stmt = $conn->prepare(file_get_contents('../SQL/insert_requests.sql'));
        $request_stmt->bind_param('ii',$enthusiast_id,$sample_id);
        $update_stmt = $conn->prepare(file_get_contents('../SQL/remove_from_inventory.sql'));
        $update_stmt->bind_param('i',$sample_id);
        if(!$request_stmt->execute() || !$update_stmt->execute()){
            ?>
                    <link rel = "stylesheet" href = "styles/stylish.css">
    <?php echo file_get_contents('../PHP/styles/header.html')?>
            <div> <h2> Failed to create request</h2> </div>
            <div><p>Failed to create request. Please try again or contact support.</p></div>
            <?php
        }
        else{
            header("Location:{$_SERVER['REQUEST_URI']}",true,303);
            exit();
        }
    }
    else{
        ?>
                <link rel = "stylesheet" href = "styles/stylish.css">
    <?php echo file_get_contents('../PHP/styles/header.html')?>
        <div> <h2>Thank you for requesting the sample! </h2></div>
        <div><p>We will contact you about the status of your request.</p></div>
        <?php
    }
    
    ?>