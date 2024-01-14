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
    session_start();?>
    <link rel = "stylesheet" href = "styles/stylish.css"><?php
    if(!isset($_SESSION['archive'])){
        ?>
        <link rel = "stylesheet" href = "styles/stylish.css">
        <?php 
            $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        ?>
        <link rel = "stylesheet" href = "styles/stylish.css">
        <?php 
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header);
        ?>
        <p><b>Only archive associates can view requests. If you are an associate, please login.</b></p>
        <?php
    }
    else{

        $dblist = 'SELECT * FROM requests_made';
        $result = $conn->query($dblist); 
        $resrows = $result->fetch_all();

        //varaibles to flag if an object has been inserted, deleted or changed
        $selected = false;

        $del_stmt = $conn->prepare(file_get_contents("../SQL/fulfill_request.sql"));
        $del_stmt->bind_param('ii', $samID, $enthID);
        
        //deletion for selected items in last post request
        for($i = 0; $i < $result->num_rows; $i++){
            $samID = $resrows[$i][0];
            $enthID = $resrows[$i][1];
            $key = "checkbox" . $samID. $enthID;
            if (isset($_POST[$key]) ){
                $selected = true;
                $del_stmt->execute(); 
            }
        }

        //counting for the number of deleted items
        if ($selected == true) {
        header("Location: {$_SERVER['REQUEST_URI']}", TRUE, 303);
        exit();
        }
    ?>
    <html>
    <link rel = "stylesheet" href = "styles/styles.css">
        <?php     $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
    <h2> Sample Requests</h2>

    <?php 
        $dblist = "SELECT * FROM requests_made" ;
        $result = $conn->query($dblist);
        result_to_html_table($result); 
    }
?>

<?php 

function result_to_html_table($result) {
        $result_body = $result->fetch_all();
        $num_rows = $result->num_rows;
        $num_cols = $result->field_count;
        $fields = $result->fetch_fields();
        $filled = "Fulfilled";
        $pending = "Pending";
        ?>
        <!-- Description of table - - - - - - - - - - - - - - - - - - - - -->
        <form action = "viewRequests.php" method = POST>
        
        <!-- Begin header - - - - - - - - - - - - - - - - - - - - -->
        <table class = striped>
        <thead>
        <tr>
        <td><b>Fulfilled</b></td>
        <?php 
        for ($i=2; $i<$num_cols; $i++){ ?>
            <td><b> <?php 
                echo $fields[$i]->name; }?></b></td>
        </tr>
        </thead>
        
        <!-- Begin body - - - - - - - - - - - - - - - - - - - - - -->
        <tbody>

        <?php for ($i=0; $i<$num_rows; $i++){ ?>
            <?php 
            $samID = $result_body[$i][0];
            $enthID = $result_body[$i][1];
             ?>
            <tr> 
            <td> <input type="checkbox"
                    name="checkbox<?php echo $samID . $enthID; ?>"
                    value=<?php echo $samID . $enthID; ?> 
                    /> </td>  
            <?php 
            for($j=2; $j<$num_cols; $j++) { 
                if(($num_cols - 1) == $j){
                    if ($result_body[$i][$j] == 0){
                        ?><td><b><?php echo $pending;?></b></td> <?php
                    }else {
                        ?><td><b><?php echo $filled;?></b></td> <?php
                    }
                } else {
                ?><td><?php echo $result_body[$i][$j];} ?></td>
            <?php } ?>
            </tr>
        <?php } ?>
        </tbody></table>
            
        <input type = "submit" value = "Mark as Fulfilled" 
        method = POST/>
    </form>
    
<?php 
} ?>