<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by GTusername4

if (!isset($_SESSION['email'])) {
	header('Location: index.php');
	exit();
}

$customerType = $_GET['customerType'];
$returnAddress = $_GET['input'];
$customerAdded = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $returnAddress = $_POST['input'];
    if (isset($_POST['street'])) {

        $email = mysqli_real_escape_string($db, $_POST['email']);
        $street = mysqli_real_escape_string($db, $_POST['street']);
        $city = mysqli_real_escape_string($db, $_POST['city']);
        $state = mysqli_real_escape_string($db, $_POST['state']);
        $zipcode = mysqli_real_escape_string($db, $_POST['zipcode']);
        $phone = mysqli_real_escape_string($db, $_POST['phone']);

        $passedValidation = true;
        if (empty($street)) {
            array_push($error_msg,  "Please enter a street.");
            $passedValidation = false;
        }

        if (empty($city)) {
            array_push($error_msg,  "Please enter a city ");
            $passedValidation = false;
        }

         if (empty($state)) {
            array_push($error_msg,  "Please enter a state.");
            $passedValidation = false;
        }

        if (empty($zipcode)) {
            array_push($error_msg,  "Please enter an zipcode.");
            $passedValidation = false;
        } else if (!ctype_digit($zipcode)) {
            array_push($error_msg,  "Please enter a valid zipcode.");
            $passedValidation = false;
        }

        if (empty($phone)) {
            array_push($error_msg,  "Please enter an phone.");
            $passedValidation = false;
        } else {
            $phone = stripPhoneNumber($phone);
            if (strlen($phone) != 10) {
                array_push($error_msg,  "Please enter a valid phone number (10 digit stored).");
                $passedValidation = false;
            }
        }

        if ($passedValidation) {
            $query = add_customer_insert_customer ($email, $street, $city, $state, $zipcode, $phone);

            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

             if (mysqli_affected_rows($db) == -1) {
                 array_push($error_msg,  "INSERT ERROR: Customer... <br>".  __FILE__ ." line:". __LINE__ );
             } else {
                 $query =  $add_customer_fetch_customerid;

                 $result = mysqli_query($db, $query);
                 include('lib/show_queries.php');

                if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
                    $customerID = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    if (isset($_POST['driverlicense'])) {
                        $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
                        $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
                        $driverlicense = mysqli_real_escape_string($db, $_POST['driverlicense']);

                        $passedValidation = true;
                        if (empty($firstname)) {
                            array_push($error_msg,  "Please enter a first name.");
                            $passedValidation = false;
                        }

                        if (empty($lastname)) {
                            array_push($error_msg,  "Please enter a last name ");
                            $passedValidation = false;
                        }

                         if (empty($driverlicense)) {
                            array_push($error_msg,  "Please enter a driver license.");
                            $passedValidation = false;
                        }

                        if ($passedValidation) {
                            $query = add_customer_insert_individual($driverlicense, $customerID['CustomerID'], $firstname, $lastname);
                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');


                             if (mysqli_affected_rows($db) == -1) {
                                 array_push($error_msg,  "INSERT ERROR: Individual... <br>".  __FILE__ ." line:". __LINE__ );
                             }
                        }
                    } else if(isset($_POST['businessid'])) {
                        $businessname = mysqli_real_escape_string($db, $_POST['businessname']);
                        $primaryname = mysqli_real_escape_string($db, $_POST['primaryname']);
                        $primarytitle = mysqli_real_escape_string($db, $_POST['primarytitle']);
                        $businessid = mysqli_real_escape_string($db, $_POST['businessid']);

                        $passedValidation = true;
                        if (empty($businessname)) {
                            array_push($error_msg,  "Please enter a business name.");
                            $passedValidation = false;
                        }

                        if (empty($primaryname)) {
                            array_push($error_msg,  "Please enter a primary contact name ");
                            $passedValidation = false;
                        }

                        if (empty($primarytitle)) {
                            array_push($error_msg,  "Please enter a primary contact title ");
                            $passedValidation = false;
                        }

                        if (empty($businessid)) {
                            array_push($error_msg,  "Please enter a busines tax id.");
                            $passedValidation = false;
                        }

                        if ($passedValidation) {
                            $query = add_customer_insert_business($businessid, $customerID['CustomerID'], $businessname, $primaryname, $primarytitle);
                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');


                            if (mysqli_affected_rows($db) == -1) {
                                array_push($error_msg,  "INSERT ERROR: Business... <br>".  __FILE__ ." line:". __LINE__ );
                            } else {
                                $customerAdded = true;
                            }
                        }
                    }
                 } else {
                    array_push($error_msg,  "Query ERROR: Failed to get new CustomerID.");
                 }
            }
        }
	}
}


function stripPhoneNumber( $phonenumber ) {
    $phonenumber = preg_replace("/[^0-9]/", '', $phonenumber);

    if (strlen($phonenumber) == 11) {
        $phonenumber = preg_replace("/^1/", '',$phonenumber);
    }

    return $phonenumber;
}
?>

<?php include("lib/header.php"); ?>
		<title>Add Customer</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name">Add Customer</div>
					<div class="features">   

                        <?php if($customerAdded) : ?>
                            <div class="profile_section">
                                <div class="subtitle">Customer Added</div>
                                <input type="button" onclick="location.href='<?php print $returnAddress; ?>';" value="Return to Previous Page" />
                            </div>
                        <?php else: ?>
                        <input type="button" onclick="location.href='<?php print $returnAddress; ?>';" value="Return to Previous Page" />

                        <div class="profile_section">
							<form name="customerTypeForm" action="add_customer.php" method="post">
								<table>
									<tr>
										<td class="item_label">Customer Type</td>
										<td>
											<select onchange="window.location.href = this.value" name="type">
                                                <option disabled selected value> -- select an option -- </option>
												<option value="add_customer.php?customerType=Individual&input=<?php print $returnAddress; ?>" <?php if ($customerType == 'Individual') { print 'selected="true"';} ?>>Individual</option>
												<option value="add_customer.php?customerType=Business&input=<?php print $returnAddress; ?>" <?php if ($customerType == 'Business') { print 'selected="true"';} ?>>Business</option>
											</select>
										</td>
									</tr>
									<input type="hidden" name="input" value="<?php print $returnAddress; ?>"/>

									<?php if ($customerType == 'Individual') : ?>
                                        <tr>
                                            <td class="item_label">Street</td>
                                            <td>
                                                <input type="text" name="street" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">City</td>
                                            <td>
                                                <input type="text" name="city" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">State</td>
                                            <td>
                                                <input type="text" name="state" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Zipcode</td>
                                            <td>
                                                <input type="text" name="zipcode" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Phone Number</td>
                                            <td>
                                                <input type="text" name="phone" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Email (optional)</td>
                                            <td>
                                                <input type="text" name="email" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">First Name</td>
                                            <td>
                                                <input type="text" name="firstname" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Last Name</td>
                                            <td>
                                                <input type="text" name="lastname" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Driver License</td>
                                            <td>
                                                <input type="text" name="driverlicense" value="" />
                                            </td>
                                        </tr>
                                    <?php elseif($customerType == 'Business') : ?>
                                        <tr>
                                            <td class="item_label">Street</td>
                                            <td>
                                                <input type="text" name="street" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">City</td>
                                            <td>
                                                <input type="text" name="city" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">State</td>
                                            <td>
                                                <input type="text" name="state" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Zipcode</td>
                                            <td>
                                                <input type="text" name="zipcode" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Phone Number</td>
                                            <td>
                                                <input type="text" name="phone" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Email (optional)</td>
                                            <td>
                                                <input type="text" name="email" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Business Name</td>
                                            <td>
                                                <input type="text" name="businessname" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Primary Contact Name</td>
                                            <td>
                                                <input type="text" name="primaryname" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Primary Contact Title</td>
                                            <td>
                                                <input type="text" name="primarytitle" value="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="item_label">Business Identification Number</td>
                                            <td>
                                                <input type="text" name="businessid" value="" />
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr><td><button href="javascript:customerTypeForm.submit();">Save</button></td></tr>

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