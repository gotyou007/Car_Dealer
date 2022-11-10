<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by GTusername4

if (!isset($_SESSION['email']) or ($_SESSION['type'] != "ServiceWriter" and $_SESSION['type'] != "Owner")) {
	header('Location: login.php');
	exit();
}

$valid_vin = false;
$open_repair = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $VIN = mysqli_real_escape_string($db, $_POST['vin']);

    $form_error = false;
	if (empty($VIN)) {
        array_push($error_msg,  "Error: You must provide a VIN");
        $form_error = true;
    }    

    if (!$form_error) {
        $query = view_repair_pull_vehicle($VIN);

        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        

        print (mysqli_num_rows($result));
        if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            array_push($error_msg,  "SELECT ERROR: Cannot pull Vehicle info <br>" . __FILE__ ." line:". __LINE__ );
            $valid_vin = false;
        }

        if ($row['VIN']  == NULL) {
            array_push($error_msg,  "Error: Cannot find Vehicle with given VIN ");
            $valid_vin = false;
        } elseif ($row['DateSold']  == NULL) {
            array_push($error_msg,  "Error: This Vehicle has not been sold yet ");
            $valid_vin = false;
        } else {
            $valid_vin = true;   
        }
    }
}



?>

<?php include("lib/header.php"); ?>
<title>View Repair</title>
</head>

<body>
    <div id="main_container">
        <div class="center_left">
        <?php include("lib/menu.php"); ?>

        <div class="center_content">
            <div class="center_left">
                <div class="profile_section">
                    <div class="subtitle">View Repair</div>   
                
                    <table>
                        <?php 
                        if ($valid_vin)	{ ?>
                        <tr>
                            <td class="item_label">VIN</td>
                            <td>
                                <?php print $row['VIN'];?>  
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Model Year</td>
                            <td>
                                <?php print $row['ModelYear'];?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Model Name</td>
                            <td>
                                <?php print $row['ModelName'];?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Vehicle Type</td>
                            <td>
                                <?php print $row['VehicleType'];?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Manufacturer</td>
                            <td>
                                <?php print $row['ManufacturerName'];?>
                            </td>
                        </tr>

                         <tr>
                            <td class="item_label">Color</td>
                            <td>
                                <?php print $row['Colors'];?>
                            </td>
                        </tr>
                        <?php 
                        } 
                        else { ?>
                        <form name="requestform" action="view_repair.php" method="POST">
                        <tr>
                            <td class="item_label">VIN</td>
                            <td><input type="text" name="vin" /></td>
                        </tr>
                
                        <tr>
                            <a href="javascript:requestform.submit();" class="fancy_button">Enter</a> 
                        </tr>
                        </form>
                        <?php
                        }?>

                        <?php
                        $repair = $db ->query(view_repair_pull_repair($VIN));
                        $r = mysqli_fetch_array($repair, MYSQLI_ASSOC);

                        if ($valid_vin && $r['VIN'] == NULL) {
                            $return_a = "create_repair.php?VIN=" . $VIN
                        ?>
                            <input type="button" onclick="location.href='<?php print $return_a; ?>'" value="Create New Repair" />
                        <?php
                        }
                        ?>

                        <?php
                        $open_repair = $db ->query(view_repair_pull_repair_open($VIN));
                        $open_r = mysqli_fetch_array($open_repair, MYSQLI_ASSOC);

                        if ($valid_vin && $open_r['VIN'] != NULL && $open_r['EndDate'] == NULL) { 
                            $redirect = "update_repair.php?VIN=" . $VIN . "&StartDate=" . $open_r['StartDate']
                            ?>
                            <input type="button" onclick="location.href='<?php print $redirect; ?>'" value="Update Repair" />
                        <?php
                        }
                        ?>

                        </form>					
                    </table>
                </div>
            </div> 

            <?php include("lib/error.php"); ?>
                    
            <div class="clear"></div> 		
        </div>    

        <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>