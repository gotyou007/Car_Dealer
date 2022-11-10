<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by GTusername4

if (!isset($_SESSION['email']) or ($_SESSION['type'] != "ServiceWriter" and $_SESSION['type'] != "Owner")) {
	header('Location: index.php');
	exit();
}

$VIN = mysqli_real_escape_string($db, $_REQUEST['VIN']);
$StartDate = mysqli_real_escape_string($db, $_REQUEST['StartDate']);

$query = update_repair_pull_repair($VIN, $StartDate);

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$LaborCharges = 0;
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($row['LaborCharges']) {
        $LaborCharges = $row['LaborCharges'];
    }
} else {
    array_push($error_msg,  "Query ERROR: Failed to get Repair info... <br>".  __FILE__ ." line:". __LINE__ );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $labor_charges = mysqli_real_escape_string($db, $_POST['labor_charges']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $part_number = mysqli_real_escape_string($db, $_POST['part_number']);
    $quantity_used = mysqli_real_escape_string($db, $_POST['quantity_used']);
    $unit_price = mysqli_real_escape_string($db, $_POST['unit_price']);
    $vendor_name = mysqli_real_escape_string($db, $_POST['vendor_name']);
    $complete_repair = mysqli_real_escape_string($db, $_POST['complete_repair']);


    $form_error = false;
    if (empty($complete_repair)) {
        array_push($error_msg,  "Error: You must provide repair completion status ");
        $form_error = true;
    }
    if ($labor_charges < $LaborCharges && $_SESSION['type'] != "Owner") {
        array_push($error_msg,  "Error: Labor Charges cannot be less than their previous value ");
        $form_error = true;
    }

    if (!$form_error) {
        $query = "";

        if (!empty($part_number)) {
            $query .= update_repair_insert_part($VIN, $StartDate, $part_number, $quantity_used, $unit_price, $vendor_name);
        }
        if (!empty($labor_charges) || !empty($description)) {
            $query .= update_repair_update_labor_charges_description ($labor_charges, $description, $VIN, $StartDate);
        }
        if ($complete_repair == "yes") {
            $date_added = date("Y/m/d");
            $query .= update_repair_update_completion($date_added, $VIN, $StartDate);
        }

        $queryID = mysqli_multi_query($db, $query);
            
        include('lib/show_queries.php');

        if ($queryID  == False) {
            array_push($error_msg, "INSERT ERROR: error updating repair: " . $VIN.  " StartDate: " . $StartDate ."<br>". __FILE__ ." line:". __LINE__ );
        } 
            
        array_push($query_msg, "sending request ... ");
        header(REFRESH_TIME . 'url=update_repair.php?VIN=' . $VIN . '&StartDate=' . $StartDate);	
    }
}

?>

<?php include("lib/header.php"); ?>
		<title>Update Repair Form</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name">Update Repair</div>
					<div class="features">   

                        <div class="profile_section">
                            <div class='subtitle'>Parts</div>
                            <table>
                                <tr>
                                    <td class='heading'>Part Number</td>
                                    <td class='heading'>Quantity Used</td>
                                    <td class='heading'>Unit Price</td>
                                    <td class='heading'>Vendor Name</td>
                                </tr>
                                <?php
                                    $parts_list = $db ->query(update_repair_pull_parts($VIN, $StartDate));

                                    if (isset($parts_list)) {
                                        while ($part = mysqli_fetch_array($parts_list, MYSQLI_ASSOC)){
                                            $row_p = urlencode($part['PartNumber']);
                                            $row_q = urlencode($part['QuantityUsed']);
                                            $row_u = urlencode($part['UnitPrice']);
                                            $row_v = urlencode($part['VendorName']);
                                            print "<tr>";
                                            print "<td>{$row_p}</td>";
                                            print "<td>{$row_q}</td>";
                                            print "<td>{$row_u}</td>";
                                            print "<td>{$row_v}</td>";
                                            print "</tr>";
                                        }
                                    }	?>
                            </table>
                        </div>
                        <div class="profile_section">
							<form name="updateRepairForm" action="update_repair.php" method="post">
								<table>
                                    <tr>
                                        <td class="item_label">Labor Charges</td>
                                        <td><input type="text" name="labor_charges" value="<?php if ($row['LaborCharges']) { print $row['LaborCharges']; } ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Description</td>
                                        <td><input type="text" name="description"  value="<?php if ($row['Description']) { print $row['Description']; } ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Part Number</td>
                                        <td><input type="text" name="part_number" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Quantity Used</td>
                                        <td><input type="number" name="quantity_used" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Unit Price</td>
                                        <td><input name="unit_price" pattern="^\d*(\.\d{0,2})?$" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Vendor Name</td>
                                        <td><input type="text" name="vendor_name" /></td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Complete Repair</td>
                                        <td>
                                            <select name="complete_repair">
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>    
                                <input type="hidden" name="VIN" value="<?php print $VIN; ?>" />
                                <input type="hidden" name="StartDate" value="<?php print $StartDate; ?>" />

                                <a href="javascript:updateRepairForm.submit();" class="fancy_button">Update</a> 

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