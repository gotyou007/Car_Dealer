<?php

include('lib/common.php');
include('lib/sqlLib.php');

    $VIN = mysqli_real_escape_string($db, $_REQUEST['VIN']);
    $query = get_vehicle_profile_sql($VIN);

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get Vehicle profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
//if owner or manager
if ($_SESSION['type'] === "Owner" or $_SESSION['type'] === "Manager") {
    //clerk query
    $clerk_query = get_clerk_sql($VIN);
    $clerk_result = mysqli_query($db, $clerk_query);
    if ( !is_bool($clerk_result) && (mysqli_num_rows($clerk_result) > 0) ) {
        $clerk_row = mysqli_fetch_array($clerk_result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get Clerk information...<br>" . __FILE__ ." line:". __LINE__ );
    }
    //sales query
    $sales_query = get_sales_query($VIN);
    $sales_result = mysqli_query($db, $sales_query);
    if ( !is_bool($sales_result) && (mysqli_num_rows($sales_result) > 0) ) {
        $sales_row = mysqli_fetch_array($sales_result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get Sales information...<br>" . __FILE__ ." line:". __LINE__ );
    }

    //Repair query
    $repair_query = get_repair_query($VIN);

    $repair_result = mysqli_query($db, $repair_query);
    if (mysqli_num_rows($repair_result) < 1) {
        array_push($error_msg,  "Query ERROR: Failed to get repair information...<br>" . __FILE__ ." line:". __LINE__ );
    }
}

?>

<?php include("lib/header.php"); ?>
<title>View Vehicle</title>
</head>

<body>
		<div id="main_container">
            <div class="center_left">
            <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">

                <div class="profile_section">
                    <div class="subtitle">Vehicle Details</div>
                    <table>
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
                            <td class="item_label">Color</td>
                            <td>
                                <?php print $row['Colors'];?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">List Price</td>
                            <td>
                                <?php print $row['ListPrice'];?>
                            </td>
                        </tr>

                        <?php if($_SESSION['type'] === "Owner" or $_SESSION['type'] === "Manager" or $_SESSION['type'] === "InventoryClerk"){ ?>
                        <tr>
                            <td class="item_label">Invoice Price</td>
                            <td>
                                <?php print $row['InvoicePrice'];?>
                            </td>
                        </tr>
                         <?php } ?>
                        <tr>
                            <td class="item_label">Description</td>
                            <td>
                                <?php print $row['Description'];?>
                            </td>
                            <?php if(($_SESSION['type'] === "SalesPeople" or $_SESSION['type'] === "Owner") AND is_null($row['DateSold'])){
                                $redirect_uri = "sell_vehicle.php?VIN=" . $row['VIN'] ?>
                            <td><a href="<?php print $redirect_uri?>" >Sell Vehicle</a></td>
                            <?php } ?>
                        </tr>


                    </table>
                </div>

            <?php if($_SESSION['type'] === "Owner" or $_SESSION['type'] === "Manager"){ ?>
            <div class="profile_section">
                <div class="subtitle">Clerk Information</div>
                <table>
                    <tr>
                        <td class="heading">Clerk Name</td>
                        <td class='heading'>Invoice Price</td>
                        <td class='heading'>Date Added</td>
                    </tr>
                    <tr>
                        <td>
                            <?php print $clerk_row['ClerkName'];?>
                        </td>
                        <td>
                            <?php print $clerk_row['InvoicePrice'];?>
                        </td>
                        <td>
                            <?php print $clerk_row['DateAdded'];?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="profile_section">
                <div class="subtitle">Sales Information</div>
                    <table>
                        <tr>
                            <td class="heading">Salesperson Name</td>
                            <td class='heading'>Customer Email</td>
                            <td class='heading'>Customer Address</td>
                            <td class='heading'>Customer Zip Code</td>
                            <td class='heading'>Customer Name</td>
                            <td class='heading'>Customer Phone</td>
                            <td class='heading'>Customer Primary Contact Name</td>
                            <td class='heading'>Customer Primary Contact Title</td>
                        </tr>
                        <tr>
                            <td>
                                <?php print $sales_row['SalespersonName'];?>
                            </td>
                            <td>
                                <?php print $sales_row['Email'];?>
                            </td>
                            <td>
                                <?php if(!empty($sales_row))
                                {print $sales_row['StreetAddress'].", ".$sales_row['City'].", ".$sales_row['State'];}?>
                            </td>
                            <td>
                                <?php print $sales_row['ZipCode'];?>
                            </td>
                            <td>
                                <?php print $sales_row['CustomerName'];?>
                            </td>
                            <td>
                                <?php print $sales_row['Phone'];?>
                            </td>
                            <td>
                                <?php print $sales_row['PrimaryContactName'];?>
                            </td>
                            <td>
                                <?php print $sales_row['PrimaryContactTitle'];?>
                            </td>
                        </tr>
                        <tr>
                            <td class="heading">Date Sold</td>
                            <td class='heading'>Sold Price</td>
                        </tr>
                        <tr>
                            <td>
                                <?php print $sales_row['DateSold'];?>
                            </td>
                            <td>
                                <?php print $sales_row['SoldPrice'];?>
                            </td>
                        </tr>
                    </table>
            </div>
                <div class="profile_section">
                    <div class="subtitle">Repair Information</div>
                    <table>
                        <tr>
                            <td class="heading">Start Date</td>
                            <td class='heading'>End date</td>
                            <td class='heading'>Labor Charges</td>
                            <td class="heading">Parts Cost</td>
                            <td class='heading'>Total Cost</td>
                            <td class='heading'>Service Writer Name</td>
                            <td class='heading'>Customer Name</td>

                        </tr>
                        <?php
                        if (isset($repair_result)) {
                            while ($repair_row = mysqli_fetch_array($repair_result, MYSQLI_ASSOC)){
                                print "<tr>";
                                print "<td>{$repair_row['StartDate']}</td>";
                                print "<td>{$repair_row['EndDate']}</td>";
                                print "<td>{$repair_row['LaborCharges']}</td>";
                                print "<td>{$repair_row['PartsCost']}</td>";
                                print "<td>{$repair_row['TotalCost']}</td>";
                                print "<td>{$repair_row['ServiceWriterName']}</td>";
                                print "<td>{$repair_row['CustomerName']}</td>";
                                print "</tr>";
                            }
                        }	?>
                    </table>
                </div>
            <?php } ?>

        </div>

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>

	</body>
</html>