<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by GTusername2

if (!isset($_SESSION['email']) or ($_SESSION['type'] != "InventoryClerk" and $_SESSION['type'] != "Owner")) {
	header('Location: login.php');
	exit();
}

$query = $add_vehicle_pull_manufacturer;
		 
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
    
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    array_push($error_msg,  "SELECT ERROR: Cannot pull Manufacturer List  <br>" . __FILE__ ." line:". __LINE__ );
}

$vehicle_type = $_GET['vehicle_type'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    $vehicle_type = mysqli_real_escape_string($db, $_POST['vehicle_type']);
    $vin = mysqli_real_escape_string($db, $_POST['vin']);
    $manufacturer = mysqli_real_escape_string($db, $_POST['manufacturer']);
    $model_year = mysqli_real_escape_string($db, $_POST['model_year']);
    $model = mysqli_real_escape_string($db, $_POST['model']);
    $colors = mysqli_real_escape_string($db, $_POST['colors']);
    $invoice_price = mysqli_real_escape_string($db, $_POST['invoice_price']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $door_count = mysqli_real_escape_string($db, $_POST['door_count']);
    $roof_type = mysqli_real_escape_string($db, $_POST['roof_type']);
    $back_seat_count = mysqli_real_escape_string($db, $_POST['back_seat_count']);
    $cargo_capacity = mysqli_real_escape_string($db, $_POST['cargo_capacity']);
    $cargo_cover_type = mysqli_real_escape_string($db, $_POST['cargo_cover_type']);
    $rear_axis_count = mysqli_real_escape_string($db, $_POST['rear_axis_count']);
    $driver_side_door = mysqli_real_escape_string($db, $_POST['driver_side_door']);
    $drive_train_type = mysqli_real_escape_string($db, $_POST['drive_train_type']);
    $cup_holder_count = mysqli_real_escape_string($db, $_POST['cup_holder_count']);

    $form_error = false;
	if (empty($vehicle_type)) {
        array_push($error_msg,  "Error: You must provide a vehicle type ");
        $form_error = true;
    }
    if (empty($vin)) {
        array_push($error_msg,  "Error: You must provide a vin ");
        $form_error = true;
    }
    if (empty($manufacturer)) {
        array_push($error_msg,  "Error: You must provide a manufacturer ");
        $form_error = true;
    }
    if (empty($model_year)) {
        array_push($error_msg,  "Error: You must provide a model year ");
        $form_error = true;
    } else if (!ctype_digit($model_year) || intval($model_year) > date("Y") + 1 || intval($model_year) < 1000) {
        array_push($error_msg,  "Error: Please enter a valid model year.");
        $form_error = true;
    }
    if (empty($model)) {
        array_push($error_msg,  "Error: You must provide a model ");
        $form_error = true;
    }
    if (empty($colors)) {
        array_push($error_msg,  "Error: You must provide colors ");
        $form_error = true;
    }
    if (empty($invoice_price)) {
        array_push($error_msg,  "Error: You must provide a invoice price ");
        $form_error = true;
    }
    if ($vehicle_type == 'Car' && empty($door_count)) {
        array_push($error_msg,  "Error: You must provide door count ");
        $form_error = true;
    }
    if ($vehicle_type == 'Convertible' && empty($roof_type)) {
        array_push($error_msg,  "Error: You must provide a roof type ");
        $form_error = true;
    }
    if ($vehicle_type == 'Convertible' && empty($back_seat_count)) {
        array_push($error_msg,  "Error: You must provide a back seat count ");
        $form_error = true;
    }
    if ($vehicle_type == 'Truck' && empty($cargo_capacity)) {
        array_push($error_msg,  "Error: You must provide a cargo capacity ");
        $form_error = true;
    }
    if ($vehicle_type == 'Truck' && empty($cargo_cover_type)) {
        array_push($error_msg,  "Error: You must provide a cargo cover type ");
        $form_error = true;
    }
    if ($vehicle_type == 'Truck' && empty($rear_axis_count)) {
        array_push($error_msg,  "Error: You must provide a rear axis count ");
        $form_error = true;
    }
    if ($vehicle_type == 'Van/Minivan' && empty($driver_side_door)) {
        array_push($error_msg,  "Error: You must provide driver side door info");
        $form_error = true;
    }
    if ($vehicle_type == 'SUV' && empty($drive_train_type)) {
        array_push($error_msg,  "Error: You must provide a drive train type ");
        $form_error = true;
    }
    if ($vehicle_type == 'SUV' && empty($cup_holder_count)) {
        array_push($error_msg,  "Error: You must provide a cup holder count ");
        $form_error = true;
    }

    if (!$form_error) {
        $date_added = date("Y/m/d");
        $query = add_vehicle_insert_vehicle($vin, $manufacturer, $model, $model_year, $invoice_price, $description, $_SESSION['email'], $date_added, $colors);

        $query .= add_vehicle_insert_type ($vehicle_type, $vin, $door_count, $roof_type, $back_seat_count, $cargo_capacity, $cargo_cover_type, $rear_axis_count, $driver_side_door, $drive_train_type, $cup_holder_count);
          
        $queryID = mysqli_multi_query($db, $query);
            
        include('lib/show_queries.php');

        if ($queryID  == False) {
            array_push($error_msg, "INSERT ERROR: error adding vehicle: " . $vin.  " manufacturer: " . $manufacturer ."<br>". __FILE__ ." line:". __LINE__ );
        } 
            
        array_push($query_msg, "sending request ... ");
        header(REFRESH_TIME . 'url=view_vehicle.php?VIN=' . $vin);	
    }
}
?>

<?php include("lib/header.php"); ?>
        <title>Add Vehicle</title>
	</head>

	<body>
		<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">
				<div class="center_left">
                    <div class="title_name">Add Vehicle</div>          
					<div class="features">   

						
						<div class="profile_section">
                            <div class="subtitle">Add Vehicle</div>   
							<form name="requestform" action="add_vehicle.php" method="POST">
							<table>								
                                
                                <tr>
									<td class="item_label">Vehicle Type</td>
									<td>
                                        <select onchange="window.location.href = this.value" name="vehicle_type">
                                            <option disabled selected value> -- select an option -- </option>
                                            <option value="add_vehicle.php?vehicle_type=Car" <?php if ($vehicle_type == 'Car') { print 'selected="true"';} ?>>Car</option>
                                            <option value="add_vehicle.php?vehicle_type=Convertible" <?php if ($vehicle_type == 'Convertible') { print 'selected="true"';} ?>>Convertible</option>
                                            <option value="add_vehicle.php?vehicle_type=Truck" <?php if ($vehicle_type == 'Truck') { print 'selected="true"';} ?>>Truck</option>
                                            <option value="add_vehicle.php?vehicle_type=Van/Minivan" <?php if ($vehicle_type == 'Van/Minivan') { print 'selected="true"';} ?>>Van/Minivan</option>
                                            <option value="add_vehicle.php?vehicle_type=SUV" <?php if ($vehicle_type == 'SUV') { print 'selected="true"';} ?>>SUV</option>
                                        </select>
                                    </td>
								</tr>	
                                <tr>
									<td class="item_label">VIN</td>
									<td><input type="text" name="vin" /></td>
								</tr>                             	
                                <tr>
									<td class="item_label">Manufacturer</td>
                                    <td>
                                        <select name="manufacturer">
                                            <option disabled selected value> -- select an option -- </option>
                                            <?php
                                            foreach($result as $i) {
                                            ?>
                                                <option value="<?php echo $i["ManufacturerName"]; ?>"><?php echo $i["ManufacturerName"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
								</tr>				
                                <tr>
									<td class="item_label">Model Year</td>
									<td><input type="text" name="model_year" /></td>
								</tr>
                                <tr>
									<td class="item_label">Model</td>
									<td><input type="text" name="model" /></td>
								</tr>
                                <tr>
									<td class="item_label">Color(s)</td>
									<td><input type="text" name="colors" /></td>
								</tr>
                                <?php 
                                if ($vehicle_type == 'Car') { 
                                ?>
                                <tr>
                                    <td class="item_label">Door Count</td>
                                    <td><input type="text" name="door_count" /></td>
                                </tr>
                                <?php } 
                                elseif ($vehicle_type == 'Convertible') { ?>
                                    <tr>
                                        <td class="item_label">Roof Type</td>
                                        <td><input type="text" name="roof_type" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Back Seat Count</td>
                                        <td><input type="text" name="back_seat_count" /></td>
                                    </tr>
                                <?php 
                                } elseif ($vehicle_type == 'Truck') { ?>
                                    <tr>
                                        <td class="item_label">Cargo Capacity</td>
                                        <td><input type="text" name="cargo_capacity" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Cargo Cover Type</td>
                                        <td><input type="text" name="cargo_cover_type" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Rear Axis Count</td>
                                        <td><input type="text" name="rear_axis_count" /></td>
                                    </tr>
                                <?php 
                                } elseif ($vehicle_type == 'Van/Minivan') { ?>
                                    <tr>
                                        <td class="item_label">Has Driver Side Door</td>
                                        <td>
                                            <select name="driver_side_door">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php 
                                } elseif ($vehicle_type == 'SUV') { ?>
                                    <tr>
                                        <td class="item_label">Drive Train Type</td>
                                        <td><input type="text" name="drive_train_type" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Cup Holder Count</td>
                                        <td><input type="text" name="cup_holder_count" /></td>
                                    </tr>
                                <?php 
                                } 
                                ?>
                                <tr>
									<td class="item_label">Invoice Price</td>
									<td><input type="text" name="invoice_price" /></td>
								</tr>
                                <tr>
									<td class="item_label">Description</td>
									<td><input type="text" name="description" /></td>
								</tr>
							</table>
                            <input type="hidden" name="vehicle_type" value="<?php print $vehicle_type; ?>" />


							<a href="javascript:requestform.submit();" class="fancy_button">Send</a> 
							</form>														
						</div>
					 </div> 	
				</div> 
                     
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    
           
               <?php include("lib/footer.php"); ?>

		</div>
	</body>
</html>