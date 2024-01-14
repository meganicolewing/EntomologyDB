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
    $reload = false;
    if((!isset($_SESSION['enthusiast_id']) && (!isset($_SESSION['archive'])))){
        ?>
        <link rel = "stylesheet" href = "styles/stylish.css">
    <?php 
    $header = '../PHP/styles/header.html';

    }
    if(isset($_POST['login'])){
        $good_input = true;
        $has_email = $conn->prepare('SELECT enthusiast_id FROM enthusiasts WHERE enthusiast_email_address = ?');
        $has_email->bind_param('s',$_POST['email']);
        if(!$has_email->execute()){
            ?>
            <p><b>Failed to login. There may not be an account associated with that email. Please try again.</b></p>
            <?php
        }
        else{
            $result = $has_email->get_result()->fetch_all();
            if($result == []){
                ?>
                <p><b>Failed to login. There may not be an account associated with that email. Please try again.</b></p>
                <?php
            }
            else{
                $reload = true;
                $_SESSION['enthusiast_id'] = $result[0][0];
            }
        }

    }
    if(isset($_POST['make'])){
        $good_input = true;
        $has_email = $conn->prepare('SELECT enthusiast_id FROM enthusiasts WHERE enthusiast_email_address = ?');
        $has_email->bind_param('s',$_POST['email']);
        if(!$has_email->execute() || !$has_email->get_result()->fetch_all()==[]){
            $good_input = false;
        }
            $check = array('street_address','city','region','code','country','email');
            for($i=0; $i<6; $i++){
                if($_POST[$check[$i]]==''){
                    $good_input = false;
                }
            }
            if($good_input){
                $result = $conn->query('SELECT enthusiast_id FROM enthusiasts');
                $num_rows = $result->num_rows;
                $data = $result->fetch_all();
                $min_id = $data[0][0];
                $max_id = $data[0][0];
                for ($i = 1; $i<$num_rows; $i++){
                    if($data[$i][0]<$min_id){
                        $min_id = $data[$i][0];
                    }
                    if($data[$i][0]>$max_id){
                        $max_id = $data[$i][0];
                    }
                }
                $enthusiast_id = $max_id + 1;
                if($min_id > 1){
                    $enthusiast_id = $min_id - 1;
                }
                $enthusiast_stmt = $conn->prepare(file_get_contents('../SQL/insert_enthusiasts.sql'));
                $enthusiast_stmt->bind_param('sssssssssi',$_POST['first_name'], $_POST['last_name'], $_POST['street_address'], $_POST['city'],
                                        $_POST['region'],$_POST['code'],$_POST['country'],$_POST['email'],$_POST['phone'],$enthusiast_id);
                if(!$enthusiast_stmt->execute()){
                    $good_input = false;
                }
            }
            if(!$good_input){
                ?>
                <p><b>Failed to store user data. You may be missing some information or that email has already been used. Plase try again.</b></p>
                <?php
            }
            else{
                $_SESSION['enthusiast_id'] = $enthusiast_id;
                $reload = true;
        }
    }
    else if(isset($_POST['archive'])){
        $_SESSION['archive'] = true;
        $reload = true;
    }
    else if(isset($_POST['logout'])){
        $reload = true;
        session_unset();
        session_destroy();
    }
    if($reload){
        header("Location:{$_SERVER['REQUEST_URI']}",true,303);
        exit();
    }
    else if(isset($_SESSION['enthusiast_id'])){
        ?>
    <link rel = "stylesheet" href = "styles/stylish.css">
    <?php 
    $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
        <div> <h2> Welcome! <h2></div>
        <div><p>You are logged in.</p></div>
        <?php

        $result = $conn->query('SELECT * FROM enthusiasts');
        $resrows = $result->fetch_all();
        $rowNum = 0;


    for($i = 0; $i < $result->num_rows; $i++){
        if ($_SESSION['enthusiast_id'] == $i){
            $enthID =  $resrows[$i][0];
            $rowNum = $i;
        }
    }

    $view_stmt = $conn->prepare(file_get_contents("../SQL/enthusiast_view.sql"));
    $view_stmt->bind_param('i', $_SESSION['enthusiast_id']);
    if(!$view_stmt->execute()){
        ?>
        <p>Failed to find Ethusiast.</p>
        <?php
    }
    $result = $view_stmt->get_result();
    $resrows = $result->fetch_all();
    
        $name = $resrows[0][0];
        $email = $resrows[0][1];
        $phone = $resrows[0][2];
        $address1 = $resrows[0][3];
        $city = $resrows[0][4];
        $region = $resrows[0][5];
        $zip = $resrows[0][6];
        $country = $resrows[0][7];
?>
    <h2>Your Information:</h2>
    <table class= "enthtable">
        <tr>
            <td><p><b>Name: </b></p></td><td><?php echo $name; ?></p></td>
        </tr>
        <tr>
            <td><p><b>Email: </b></p></td><td><?php echo $email; ?></p></td>
        </tr>
        <tr>
            <td><p><b>Phone: </b></p></td><td><?php echo $phone; ?></p></td>
        </tr>
        <tr>
            <td><p><b>Address: </b></p></td><td><?php echo $address1; ?></p></td>
        </tr>
        <tr>
            <td><p><b>         </b></p></td><td><?php echo $city; ?></p></td>
        </tr>
        <tr>
            <td><p><b>         </b></p></td><td><?php echo $region; ?>, <?php echo $zip; ?></p></td>
        </tr>
        <tr>
            <td><p><b>         </b></p></td><td><?php echo $country; ?></p></td>
        </tr>
    </table>
    
        <form action=login.php method=POST>
            <input type='submit' name='logout' value='Logout'/>
        </form>
        <!-- <a href='viewLoggedInfo.php'>Click here to view your info</a> -->
        <?php
    }
    else if(isset($_SESSION['archive'])){
        ?>
    <link rel = "stylesheet" href = "styles/stylish.css">
    <?php     $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
        <div><h2>Welcome!</h2></div>
        <div><p>You are logged in as an archive associate.</p></div>
        <form action=login.php method=POST>
            <input type='submit' name='logout' value='Logout'/>
        </form>
        <?php
    }
    else{
        ?>
        <link rel = "stylesheet" href = "styles/styles.css">
    <?php     $header = '../PHP/styles/header.html';
    if(isset($_SESSION['archive'])){
        $header = '../PHP/styles/archive_header.html';
    }
    echo file_get_contents($header)?>
        <h2>Enthusiast Login:</h2>
        <h3>Login:</h3>
        <form action='login.php' method=POST>
            <table><tbody><tr>
                <td>Email Address:</td>
                <td> <input type='email' name='email'/></td>
            </tr></tbody></table>
            <input type='submit' name='login' value='Login'/>
        </form>   
        <h3>Make an Account:</h3>
        <form action='login.php' method=POST>
            <table><tbody><tr>
                <td>First Name:</td>
                <td><input type="text" name="first_name" required/></td>
                <td>Last Name:</td>
                <td><input type='text' name='last_name' required/></td>
            </tr><tr>
                <td><h3><b>Shipping Address:</b></h3></td>
            </tr><tr>
                <td>Street Address:</td>
                <td><input type='text' name='street_address' required/></td>
                <td>City:</td>
                <td><input type='text' name='city' required/></td>
            </tr><tr>
                <td>Region/State:</td>
                <td><input type='text' name='region'  required/></td>
                <td>Postal Code:</td>
                <td><input type='text' name='code' required/></td>
            </tr><tr>
                <td>Country:</td>
                <td><input type='text' name='country' required/></td>
            </tr><tr>
                <td>Email Address:</td>
            <td> <input type='email' name='email' required/></td>
                <td>Phone Number:</td>
                <td><input type='text' name='phone' required/></td>
            </tr></tbody></table>
            <input type='submit' name='make' value='Create'/>
        </form>

    <h2>Archive Associate Login:</h2>
    <form action='login.php' method=POST>
        <table><tbody><tr>
            <td>Username:</td>
            <td><input type='text' name='user'/></td>
        </tr><tr>
            <td>Password:</td>
            <td><input type='password' name='pass'/></td>
        </tr></tbody></table>
        <input type='submit' name='archive' value='Login'/>
    </form>
    <?php
    }