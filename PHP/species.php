<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require 'result_to_table.php';

    // echo "<h1>" . getcwd() . "</h1>";
    $abs_path = realpath('../../mysqli.ini');
    // echo "<h1>[" . $abs_path . "]</h1>";
    $config = parse_ini_file($abs_path);    
    $dbname = 'entomological_archive';
    $conn = new mysqli(
            $config['mysqli.default_host'],
            $config['mysqli.default_user'],
            $config['mysqli.default_pw'],
            $dbname);
    session_start();
    // Connect to database
    if ($conn->connect_errno) {
        echo "Error: Failed to make a MySQL connection: ". "<br>";
        echo "Errno: " . $conn->connect_errno . "\n";
        echo "Error: " . $conn->connect_error . "\n";
        exit(); // Quit this PHP script if the connection fails.
    }
    if(isset($_GET['searchTaxon'])){
        $query_view = file_get_contents('../SQL/taxon_search_query.sql');
        $stmt = $conn->prepare($query_view);
        $stmt->bind_param('sssss',$_GET['taxon'],$_GET['taxon'],$_GET['taxon'],$_GET['taxon'],$_GET['taxon']);
    }
    else if(isset($_GET['searchSpecies'])){
        $query_view = file_get_contents('../SQL/species_search_query.sql');
        $stmt = $conn->prepare($query_view);
        $stmt->bind_param('s',$_GET['species']);
    }
    else if(isset($_GET['searchTag'])){
        $query_view = file_get_contents('../SQL/tag_search_query.sql');
        $stmt = $conn->prepare($query_view);
        $stmt->bind_param('s',$_GET['tag']);
    }
    else{
        $query_view = "SELECT * FROM general_species;";
        $stmt = $conn->prepare($query_view);
    }


    // Query DB for general species view

    if (!$stmt->execute()){
        echo "SELECT for General Species View failed!\n";
        echo "[".$stmt."]";
        exit();
    }

    // $stmt->bind_param("sss", $genus, $name, $tag);

    $result = $stmt->get_result();

    $conn->close();

    if (!$result){
        echo "Query failed! <br>";
        echo "Unable to execute query: ".$query_view;
        exit();
    }



?>

<!DOCTYPE html>
<html>
<link rel = "stylesheet" href = "styles/stylish.css">
    <?php 
        $header = '../PHP/styles/header.html';
        if(isset($_SESSION['archive'])){
            $header = '../PHP/styles/archive_header.html';
        }
        echo file_get_contents($header)?>
    <head>
        <title>Species Tables</title>
    </head>
    <body>
        <h1>General Species Information</h1>
        <h3>Search for a species:</h3>
        <table>
            <thead>
                <tr>
                </tr>
            </thead>
            <tbody>
            <tr>
            <td><b>Taxonomic Classification:</b></td>
        <form action="species.php" method=GET>
           <td> <input type='text' name='taxon'></td>
           <td> <input type='submit' name='searchTaxon'/> </td>
           <?php
           if(isset($_GET['taxon'])){?>
           <td><?=$_GET['taxon']?></td>
           <?php 
           }
           ?>

        </form>
            </tr>
            <tr>
            <td><b>Species:</b></td>                    
            <form action="species.php" method=GET>
                <td> <input type='text' name='species'></td>
                <td> <input type='submit' name='searchSpecies'/></td>
                <?php
                    if(isset($_GET['species'])){?>
                    <td><?=$_GET['species']?></td>
                    <?php
                }
                ?>
            </form>
            </tr>
            <tr>
            <td><b>Tag:</b></td>
            <form action="species.php" method=GET>
                <td> <input type='text' name='tag'></td>
                <td> <input type='submit' name='searchTag' value='Submit'/></td>
                <?php
                    if(isset($_GET['tag'])){?>
                    <td><?=$_GET['tag']?></td>
                    <?php
                }
                ?>            
            </form>
            </tr>
        </tbody>
        
        </table>
        <form action="species.php" method=GET>
            <input type='submit' name='remove' value='Remove All Filters'/>
        </form>
        <br>
        <?php
            result_to_species_table($result);
        ?>
    </body>
</html>