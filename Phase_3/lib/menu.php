
			<div id="header">
                <!--div class="logo"><img src="img/gtonline_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/></div-->
			</div>
			
			<div class="nav_bar">
				<ul>
                    <?php
                    if (!isset($_SESSION['email'])) {
                        if ($current_filename=='login.php') {
                            print '<li><a href="login.php" class="active">Login</a></li>';
                        } else {
                            print '<li><a href="login.php" >Login</a></li>';
                        }
                    }
                    ?>
					<li><a href="search_vehicle.php" <?php if(strpos($current_filename, 'search_vehicle.php') !== false) echo "class='active'"; ?>>Search Vehicle</a></li>

                    <?php
                    if (isset($_SESSION['email'])) {
                        if ($current_filename=='search_by_VIN.php') {
                            print '<li><a href="search_by_VIN.php" class="active">Search by VIN</a></li>';
                        } else {
                            print '<li><a href="search_by_VIN.php" >Search by VIN</a></li>';
                        }
                    }
                    ?>

                    <?php
                    if ($_SESSION['type'] === "InventoryClerk" or $_SESSION['type'] === "Owner") {
                        if ($current_filename=='add_vehicle.php') {
                            print '<li><a href="add_vehicle.php" class="active">Add Vehicle</a></li>';
                        } else {
                            print '<li><a href="add_vehicle.php" >Add Vehicle</a></li>';
                        }
                    }
                    ?>

                    <?php
                    if ($_SESSION['type'] === "ServiceWriter" or $_SESSION['type'] === "Owner") {
                        if ($current_filename=='view_repair.php') {
                            print '<li><a href="view_repair.php" class="active">View Repair</a></li>';
                        } else {
                            print '<li><a href="view_repair.php" >View Repair</a></li>';
                        }
                    }
                    ?>

                    <?php
                    if ($_SESSION['type'] === "Manager" or $_SESSION['type'] === "Owner" ) {
                        if ($current_filename=='reports.php') {
                            print '<li><a href="reports.php" class="active">Reports</a></li>';
                        } else {
                            print '<li><a href="reports.php" >Reports</a></li>';
                        }
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['email'])) {
                        print '<li><a href="logout.php" <span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>';
                    }
                    ?>
				</ul>
			</div>