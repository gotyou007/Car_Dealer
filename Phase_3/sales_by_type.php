<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = $sales_by_type_sql;
         
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Sales by Type</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Sales by Type</div>   
							
							<table>
								<tr>
									<td class="heading">Vehicle type</td>
									<td class="heading">All time</td>
									<td class="heading">Previous year</td>
									<td class="heading">Previous 30 days</td>
								</tr>
								<?php
									$types_included = array("Car", "Convertible", "Truck", "SUV", "Van");
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['VehicleType']}</td>";
										print "<td>{$row['AllTime']}</td>";
										print "<td>{$row['PrevYear']}</td>";
										print "<td>{$row['PrevMonth']}</td>";
										print "</tr>";
										$types_included = array_diff($types_included, array($row['VehicleType']));						
									}

									foreach ($types_included as $type_unsold) {
										print "<tr>";
										print "<td>{$type_unsold}</td>";
										print "<td>0</td>";
										print "<td>0</td>";
										print "<td>0</td>";
										print "</tr>";
									}
								?>
							</table>						
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