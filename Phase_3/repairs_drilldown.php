<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$select_make = $_GET['select_make'];

include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Repairs by Type/Model</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Repairs by Type/Model</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">Type/Model</td>
									<td class="heading">Number of repairs</td>
									<td class="heading">Total parts cost</td>
									<td class="heading">Total labor charges</td>
									<td class="heading">Total cost</td>
								</tr>
								<?php
									$query = get_repair_by_make_sql($select_make);
									
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>";
										print "<tr>";
										print "<td><b>{$row['VehicleType']}</b></td>";
										print "<td><b>{$row['RepairCount']}</b></td>";
										print "<td><b>{$row['TotalPartsCost']}</b></td>";
										print "<td><b>{$row['TotalLaborCharges']}</b></td>";
										print "<td><b>{$row['TotalRepairCost']}</b></td>";
										print "</tr>";

										$subquery = get_repair_by_make_type_sql($select_make, $row['VehicleType']);
										
										$subresult = mysqli_query($db, $subquery);
										while ($subrow = mysqli_fetch_array($subresult, MYSQLI_ASSOC)){
											print "<tr>";
											print "<td>{$subrow['ModelName']}</td>";
											print "<td>{$subrow['RepairCount']}</td>";
											print "<td>{$subrow['TotalPartsCost']}</td>";
											print "<td>{$subrow['TotalLaborCharges']}</td>";
											print "<td>{$subrow['TotalRepairCost']}</td>";
											print "</tr>";
										}
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