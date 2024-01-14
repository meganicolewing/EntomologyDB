<?php

function result_to_html_table($result) {
        // $result is a mysqli result object. This function formats the object as an
        // HTML table. Note that there is no return, simply call this function at the 
        // position in your page where you would like the table to be located.

        $result_body = $result->fetch_all();
        $num_rows = $result->num_rows;
        $num_cols = $result->field_count;
        $fields = $result->fetch_fields();
        ?>
        <!-- Description of table - - - - - - - - - - - - - - - - - - - - -->
        <!-- <p>This table has <?php //echo $num_rows; ?> and <?php //echo $num_cols; ?> columns.</p> -->
        
        <!-- Begin header - - - - - - - - - - - - - - - - - - - - -->
        <table>
        <thead>
        <tr>
        <?php for ($i=0; $i<$num_cols; $i++){ ?>
            <td><b><?php echo $fields[$i]->name; ?></b></td>
        <?php } ?>
        </tr>
        </thead>
        
        <!-- Begin body - - - - - - - - - - - - - - - - - - - - - -->
        <tbody>
        <?php for ($i=0; $i<$num_rows; $i++){ ?>
            <?php $id = $result_body[$i][0]; ?>
            <tr>     
            <?php for($j=0; $j<$num_cols; $j++){ ?>
                <td><?php echo $result_body[$i][$j]; ?></td>
            <?php } ?>
            </tr>
        <?php } ?>
        </tbody></table>
<?php }

function result_to_clickable_table($result) {
        // $result is a mysqli result object. This function formats the object as an
        // HTML table. Note that there is no return, simply call this function at the 
        // position in your page where you would like the table to be located.

        $result_body = $result->fetch_all();
        $num_rows = $result->num_rows;
        $num_cols = $result->field_count;
        $fields = $result->fetch_fields();
        ?>
        <!-- Description of table - - - - - - - - - - - - - - - - - - - - -->
        <!-- <p>This table has <?php //echo $num_rows; ?> and <?php //echo $num_cols; ?> columns.</p> -->
        
        <!-- Begin header - - - - - - - - - - - - - - - - - - - - -->
        <table class = striped>
        <thead>
        <tr>
            
        <?php for ($i=0; $i<$num_cols-1; $i++){ ?>
            <td><b><?php echo $fields[$i]->name; ?></b></td>
        <?php } ?>
        <td><b>Request</b></td>
        </tr>
        </thead>
        
        <!-- Begin body - - - - - - - - - - - - - - - - - - - - - -->
        <tbody>
        <form action="makeRequest.php" method=GET>
        <?php for ($i=0; $i<$num_rows; $i++){ ?>
            <?php $id = $result_body[$i][0]; ?>
            <tr>   
                <!-- <td>  
                    <input type="submit" name="sample<?=$i?>" value="Request this Sample"/>
                </td> -->
            <?php for($j=0; $j<$num_cols-1; $j++){ ?>
                <td><?php echo $result_body[$i][$j]; ?></td>  
            <?php } ?>
            <td class = "buttonTD">  
                <input type="submit" name="sample<?=$i?>" value="Request this Sample"/>
            </td>
            </tr>
        <?php } ?>
        </tbody>
        </form>
        </table>
<?php }

function result_to_formatted_table($result) {
    // $result is a mysqli result object. This function formats the object as an
    // HTML table. Note that there is no return, simply call this function at the 
    // position in your page where you would like the table to be located.

    $result_body = $result->fetch_all();
    $num_rows = $result->num_rows;
    $num_cols = $result->field_count;
    $fields = $result->fetch_fields();
    ?>
    <!-- Description of table - - - - - - - - - - - - - - - - - - - - -->
    <!-- <p>This table has <?php //echo $num_rows; ?> and <?php //echo $num_cols; ?> columns.</p> -->
    
    <!-- Begin header - - - - - - - - - - - - - - - - - - - - -->
    <table>
    <thead>
    <tr>
    <?php for ($i=0; $i<$num_cols-1; $i++){ ?>
        <td><b><?php echo $fields[$i]->name; ?></b></td>
    <?php } ?>
    </tr>
    </thead>
    
    <!-- Begin body - - - - - - - - - - - - - - - - - - - - - -->
    <tbody>
    <?php for ($i=0; $i<$num_rows; $i++){ ?>
        <?php $id = $result_body[$i][0]; ?>
        <tr>
        <?php for($j=0; $j<$num_cols-1; $j++){ ?>
            <td><?php echo $result_body[$i][$j]; ?></td>
        <?php } ?>
        </tr>
    <?php } ?>
    </tbody></table>
<?php } 


function result_to_species_table($result) {
    // $result is a mysqli result object. This function formats the object as an
    // HTML table. Note that there is no return, simply call this function at the 
    // position in your page where you would like the table to be located.

    $result_body = $result->fetch_all();
    $num_rows = $result->num_rows;
    $num_cols = $result->field_count;
    $fields = $result->fetch_fields();
    ?>
    <!-- Description of table - - - - - - - - - - - - - - - - - - - - -->
    <!-- <p>This table has <?php //echo $num_rows; ?> and <?php //echo $num_cols; ?> columns.</p> -->
    
    <!-- Begin header - - - - - - - - - - - - - - - - - - - - -->
    <table class = "striped">
    <thead>
    <tr>
    <td><b>Common Name</b></td>
    <?php for ($i=1; $i<$num_cols; $i++){ ?>
        <td><b><?php echo $fields[$i]->name; ?></b></td>
    <?php } ?>
    </tr>
    </thead>
    
    <!-- Begin body - - - - - - - - - - - - - - - - - - - - - -->
    <tbody>
    <form action="specific.php" method=GET>
    <?php for ($i=0; $i<$num_rows; $i++){ ?>
        <?php $id = $result_body[$i][0]; 
        ?>
        <tr>   
        <td>  
            <button type="submit" name="<?php echo $result_body[$i][1];?>" class = "btn-link"><?php echo $result_body[$i][0]; ?></button>
            <?php echo $result_body[$i][1];?>
        </td>
        <?php for($j = 1; $j<$num_cols; $j++){ ?>
            <td>
            <?php echo $result_body[$i][$j]; ?></td>
        <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
    </form>
    </table>
<?php }
?>

<?php 

function result_to_insert_form($result, $link){
    $num_cols = $result->field_count;
    $fields = $result->fetch_fields();
    ?>
    <form action = '<?=$link?>' method ='POST'>
        <table><tbody>
    <?php
    for($i=0;$i<$num_cols;$i++){
        ?>
        <tr>
            <td><?=$fields[$i]->name?>:</td>
            <td><input type=text name='<?=$fields[$i]->name?>'/></td>
        </tr>
        <?php
    }
    ?>
        </tbody></table>
        <input type='submit' name='insert' value='Submit'/>
    </form>
    <?php
}


function result_to_radio($result,$name){
    $result_body = $result->fetch_all();
    $num_rows = $result->num_rows;
    $num_cols = $result->field_count;
    $fields = $result->fetch_fields();
    ?>
    <!-- Description of table - - - - - - - - - - - - - - - - - - - - -->
    <!-- <p>This table has <?php //echo $num_rows; ?> and <?php //echo $num_cols; ?> columns.</p> -->
    
    <!-- Begin header - - - - - - - - - - - - - - - - - - - - -->
    <table>
    <thead>
    <tr>
        <td><b>Select</b></td>
    <?php for ($i=0; $i<$num_cols-1; $i++){ ?>
        <td><b><?php echo $fields[$i]->name; ?></b></td>
    <?php } ?>
    </tr>
    </thead>
    
    <!-- Begin body - - - - - - - - - - - - - - - - - - - - - -->
    <tbody>
    <?php for ($i=0; $i<$num_rows; $i++){ ?>
        <?php $id = $result_body[$i][$num_cols-1]; ?>
        <tr>   
            <td>  
                <input type="radio" name="<?=$name?>" value='<?=$id?>'/>
            </td>
        <?php for($j=0; $j<$num_cols-1; $j++){ ?>
            <td><?php echo $result_body[$i][$j]; ?></td>
        <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
    </form>
    </table>
    <?php
}