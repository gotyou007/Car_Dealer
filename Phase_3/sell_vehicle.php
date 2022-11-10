<?php

include('lib/common.php');
include('lib/sqlLib.php');



if (!isset($_SESSION['email']) or ($_SESSION['type'] != "SalesPeople" and $_SESSION['type'] != "Owner")) {
	header('Location: index.php');
	exit();
}

$VIN = $_GET['VIN'];

$searchMade = false;
$saleMade = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ID'])) {

        $ID = mysqli_real_escape_string($db, $_POST['ID']);
        $VIN = mysqli_real_escape_string($db, $_POST['VIN']);

        $query = search_business($ID);

        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
            $customer = mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            $query = search_individual($ID);

            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
                $customer = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }

        }
        $searchMade = true;
	}
	else if(isset($_POST['vin'])) {
	    $VIN = mysqli_real_escape_string($db, $_POST['vin']);
	    $vin = mysqli_real_escape_string($db, $_POST['vin']);
        $soldprice = mysqli_real_escape_string($db, $_POST['soldprice']);
        $solddate = mysqli_real_escape_string($db, $_POST['solddate']);
        $customerid = mysqli_real_escape_string($db, $_POST['CustomerID']);

        $passedValidation = true;

        if (empty($customerid)) {
            array_push($error_msg,  "Please lookup a customer.");
            $passedValidation = false;
        }

        if (empty($vin)) {
            array_push($error_msg,  "Please enter a VIN.");
            $passedValidation = false;
        }

        if (!is_date($solddate)) {
            array_push($error_msg,  "Please enter a valid sales date ");
            $passedValidation = false;
        }

        if (empty($soldprice)) {
            array_push($error_msg,  "Please enter a sales price ");
            $passedValidation = false;
        } else {
            $query = find_invoice_price_by_vin($vin);

            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
                $price = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if ($_SESSION['type'] === "SalesPeople") {
                    if ($soldprice <= $price['InvoicePrice'] * 0.95) {
                        array_push($error_msg,  "Error: Sale prices less than 95% of invoice price.");
                        $passedValidation = false;
                    }
                }
            } else {
                array_push($error_msg,  "Error: Failed to fetch invoice price.");
                $passedValidation = false;
            }

        }
        if ($passedValidation) {

            $query = sell_vehicle($customerid, $soldprice, $solddate, $_SESSION['email'], $vin);
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg,  "INSERT ERROR: Business... <br>".  __FILE__ ." line:". __LINE__ );
            } else {
                $saleMade = true;
            }

        }
	}
}

function is_date( $str ) {
	$stamp = strtotime( $str );
	if (!is_numeric($stamp)) {
		return false;
	}
	$month = date( 'm', $stamp );
	$day   = date( 'd', $stamp );
	$year  = date( 'Y', $stamp );

	if (checkdate($month, $day, $year)) {
		return true;
	}
	return false;
}

?>

<?php include("lib/header.php"); ?>
<title>Sell Vehicle</title>
</head>

<body>
	<div id="main_container">
        <?php include("lib/menu.php"); ?>

        <div class="center_content">
            <div class="center_left">
                <div class="title_name">Sell Vehicle</div>
                <div class="features">
                    <?php if($saleMade) : ?>
                        <div class="profile_section">
                            <div class="subtitle">Vehicle Sold</div>
                            <input type="button" onclick="location.href='index.php'" value="Return to Main Page" />
                        </div>
                    <?php else: ?>

                    <?php if(!$searchMade) : ?>
                    <div class="profile_section">
                        <div class="subtitle">Search Customer</div>
                        <form name="searchCustomerForm" action="sell_vehicle.php" method="POST">
                            <table >
                                <tr>
                                    <td class="item_label">Customer ID</td>
                                    <td><input type="textbox" name="ID" /></td>
                                    <input type="hidden" name="VIN" value="<?php print $VIN; ?>"/>
                                    <td><button href="javascript:searchCustomerForm.submit();">Search</button></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <?php endif; ?>
                    <div class="profile_section">
                        <form name="sellVehicleForm" action="sell_vehicle.php" method="POST">
                            <table>
                                <?php if($searchMade AND $customer['CustomerID'] != null) : ?>
                                    <tr>
                                        <td class="item_label">Customer Name</td>
                                        <td>
                                            <input type="text" name="CustomerName" value="<?php if ($customer['CustomerID'] != null AND $customer['BusinessName'] != null) { print $customer['BusinessName'];} else if ($customer['CustomerID'] != null AND $customer['IndividualName'] != null)  { print $customer['IndividualName'];} ?>" readonly/>
                                            <input type="hidden" name="CustomerID" value="<?php print $customer['CustomerID']; ?>"/>
                                        </td>
                                    </tr>
                                <?php elseif($searchMade AND $customer['CustomerID'] == null) : ?>
                                    <tr>
                                        <td class="item_label">Customer</td>
                                        <td>Not Found</td>
                                        <td> <input type="button" onclick="location.href='add_customer.php?input=sell_vehicle.php?VIN=<?php print $VIN; ?>';" value="Add Customer" /></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="item_label">VIN</td>
                                    <td>
                                        <input type="text" name="vin" value="<?php print $VIN; ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Sold Price</td>
                                    <td>
                                        <input type="text" name="soldprice" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Sold Date</td>
                                    <td>
                                        <input type="text" name="solddate" value="" />
                                    </td>
                                </tr>
                                <tr><td><button href="javascript:sellVehicleForm.submit();">Finalize Sale</button></td></tr>
                            </table>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
        <?php include("lib/footer.php"); ?>
    </div>
</body>
</html>