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
    $result = $conn->query('SELECT * FROM species');
    $resrows = $result->fetch_all();
    $rowNum = 0;
    $name =  $resrows[0][1];
    $taxon = $resrows[0][2];
    
    for($i = 0; $i < $result->num_rows; $i++){
        $key = $resrows[$i][2];
        if (isset($_GET[$key])){
            echo $_GET[$key];
            $name =  $resrows[$i][1];
            $taxon = $resrows[$i][2];
            $rowNum = $i;
        }
        
    }
    $view_stmt = $conn->prepare(file_get_contents("../SQL/select_specific_species.sql"));
    $view_stmt->bind_param('ss', $name, $taxon);
    if(!$view_stmt->execute()){
        ?>
        <p>Failed to find species.</p>
        <?php
    }
    $result = $view_stmt->get_result();
    
    $resrows = $result->fetch_all();
    
    //deletion for selected items in last post request
    
    //$result = $conn->query('SELECT * FROM specific_species_view');
   
?>

<link rel = "stylesheet" href = "styles/stylish.css">
    <?php     $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
    <h2> Species Information: </h2>
    <?php
            $name =  $resrows[0][0];
            $taxon = $resrows[0][7] . " " . $resrows[0][6] . " " .  $resrows[0][5] . " " .  $resrows[0][4] . " " .  $resrows[0][3];
            $photo =  $resrows[0][1];
            $barcode = $resrows[0][2];
            $commonName = $resrows[0][8];

            ?> 
            <table class = "stripedL">
            <tr>
            <td> <p><b>Common Name: <b></p> </td> <td><?php
            echo $commonName;
            ?> </td> </tr>
            <tr>
            <td> <p><b>Binomial Name: <b></p> </td> <td><?php
            echo $name;
            ?> </td> </tr>
            <tr>
            <td><p><b>Species Taxonomy: <b></p> </td><td><?php
            echo $taxon;
            ?> <td> </tr> 
            <tr>
            </table>
            <table>
                <tr>
                <td> <p><b>Photo: <b></p> </td> 
                <td><p><b>Species Barcode: <b></p> </td>
                </tr>
            <tr>   
            <td> <img src = "<?php
            echo $photo;?> " width= 200px height = 200px>
            </td>
            
             <td> <img src = "<?php
            echo $barcode;?> " width= 200px height = 200px >
    </td></tr>
    </table>