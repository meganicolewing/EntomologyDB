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
    else if(isset($_POST['make'])){
        $enthusiast_id = $_POST['enthusiasts'];
        $sample_id = $_POST['samples'];
        echo $enthusiast_id;
        echo $sample_id;
        $request_stmt = $conn->prepare(file_get_contents('../SQL/insert_requests.sql'));
        $request_stmt->bind_param('ii',$enthusiast_id,$sample_id);
        $update_stmt = $conn->prepare(file_get_contents('../SQL/remove_from_inventory.sql'));
        $update_stmt->bind_param('i',$sample_id);
        if(!$request_stmt->execute() || !$update_stmt->execute()){
            ?> 
            <p>Failed to add request.</p>
            <?php
        }
        else{
            header("Location:{$_SERVER['REQUEST_URI']}",true,303);
            exit();
        }
    }
    else{
        ?>
        <h2>Add a Request</h2>
        <form action = 'addRequest.php' method='POST'>
            <h3>Select an Enthusiast</h3>
        <?php
        $result = $conn->query(file_get_contents('../SQL/enthusiast_view_for_archive.sql'));
        result_to_radio($result,'enthusiasts');
        ?>
        <h3>Select a Sample</h3>
        <?php
        $result = $conn->query('SELECT * FROM samples_view;');
        result_to_radio($result,'samples');
        ?>
        <input type='submit' name='make' value='Submit'/>
    </form>
    <?php
    }
    ?>