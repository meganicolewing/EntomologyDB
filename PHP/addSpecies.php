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
        if(isset($_POST['Binomen']) && $_POST['Binomen'] != '' && $_POST['Taxon']!=''){
            $insert_stmt = $conn->prepare(file_get_contents('../SQL/insert_species.sql'));
            $insert_stmt->bind_param('sss',$_POST['Name'],$_POST['Binomen'],$_POST['Taxon']); //,$_POST['Photo'],$_POST['Barcode']);
            if(!$insert_stmt->execute()){
                $good_insert = false;
                ?>
                <h3>Addition failed. That species may already be in the database.</h3>
                <?php
            }
        }
        else if(isset($_POST['Taxon'])&&isset($_POST['Supertaxon']) && $_POST['Taxon']!='' && $_POST['Supertaxon']!=''){
            $insert_stmt = $conn->prepare(file_get_contents('../SQL/insert_classifications.sql'));
            $insert_stmt->bind_param('ss',$_POST['Taxon'],$_POST['Supertaxon']);
            if(!$insert_stmt->execute()){
                $good_insert = false;
                ?>
                <h3>Addition failed. That taxon may already be in the database.</h3>
                <?php
            }
        }
        else if(isset($_POST['Tag'])){
            $insert_stmt = $conn->prepare(file_get_contents('../SQL/insert_tags.sql'));
            $insert_stmt->bind_param('s',$_POST['Tag']);
            if(!$insert_stmt->execute()){
                $good_insert = false;
                ?>
                <h3>Addition failed. That tag may already be in the database.</h3>
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
        <h2>Add a Species</h2>
        <?php
        $result = $conn->query("SELECT species_common_name as 'Name', species_binomial_name as 'Binomen', classifications_taxon as 'Taxon', species_photo as 'Photo', species_barcode as 'Barcode' FROM species;");
        result_to_insert_form($result,'addSpecies.php');
        ?>
        <h2>Add a Taxon</h2>
        <?php
        $result = $conn->query("SELECT classifications_taxon as 'Taxon', classifications_supertaxon as 'Supertaxon' FROM classifications;");
        result_to_insert_form($result,'addSpecies.php');
        ?>
        <h2>Add a Tag</h2>
        <?php
        $result = $conn->query("SELECT tags_tag as 'Tag' FROM tags;");
        result_to_insert_form($result,'addSpecies.php');
    }
    ?>