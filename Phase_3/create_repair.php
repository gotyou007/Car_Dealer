<?php

include('lib/common.php');
include('lib/sqlLib.php');



if (!isset($_SESSION['email'])  or ($_SESSION['type'] != "ServiceWriter" and $_SESSION['type'] != "Owner")) {
	header('Location: index.php');
	exit();
}

$VIN = $_GET['VIN'];
$searchMade = false;
$repairCreated = false;
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
        $odometer = mysqli_real_escape_string($db, $_POST['odometer']);
        $description = mysqli_real_escape_string($db, $_POST['description']);
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

        if (empty($odometer)) {
            array_push($error_msg,  "Please enter a odometer reading ");
            $passedValidation = false;
        } else if (!ctype_digit($odometer)) {
             array_push($error_msg,  "Please enter a valid odometer value.");
             $passedValidation = false;
         }

        if (empty($description)) {
            array_push($error_msg,  "Please enter a description");
            $passedValidation = false;
        }

        $startdate = date("Y-m-d");

        if ($passedValidation) {

            $query = create_repair ($vin, $startdate, $customerid, $odometer, $description, $_SESSION['email']);
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg,  "INSERT ERROR: Business... <br>".  __FILE__ ." line:". __LINE__ );
            } else {
                $repairCreated = true;
            }
        }
	}
}

?>

<?php include("lib/header.php"); ?>
<title>Create Repair</title>
</head>

<body>
	<div id="main_container">
        <?php include("lib/menu.php"); ?>

        <div class="center_content">
            <div class="center_left">
                <div class="title_name">Create Repair</div>
                <div class="features">
                    <?php if($repairCreated) : ?>
                        <div class="profile_section">
                            <div class="subtitle">Repair Created</div>
                            <input type="button" onclick="location.href='update_repair.php?VIN=<?php print $vin; ?>&StartDate=<?php print $startdate; ?>';" value="Update Repair" />
                        </div>
                    <?php else: ?>

                    <?php if(!$searchMade) : ?>
                    <div class="profile_section">
                        <div class="subtitle">Search Customer</div>
                        <form name="searchCustomerForm" action="create_repair.php" method="POST">
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
                        <form name="createRepairForm" action="create_repair.php" method="POST">
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
                                        <td> <input type="button" onclick="location.href='add_customer.php?input=create_repair.php?VIN=<?php print $VIN; ?>';" value="Add Customer" /></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="item_label">VIN</td>
                                    <td>
                                        <input type="text" name="vin" value="<?php print $VIN; ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Odometer Reading</td>
                                    <td>
                                        <input type="text" name="odometer" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Description</td>
                                    <td>
                                        <input type="text" name="description" value="" size="60" />
                                    </td>
                                </tr>
                                <tr><td><button href="javascript:createRepairForm.submit();">Create Repair</button></td></tr>
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