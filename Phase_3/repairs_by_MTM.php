<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = $repair_by_manufacturer_sql;

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Repairs by Manufacturer</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Repairs by Manufacturer</div>   
							
							<table style="width: 200%;">
								<tr>
									<td class="heading">Manufacturer</td>
									<td class="heading">Number of repairs</td>
									<td class="heading">Total parts cost</td>
									<td class="heading">Total labor charges</td>
									<td class="heading">Total repair cost</td>
								</tr>
								<?php
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>".($row['RepairCount'] > 0 ? "<a href='repairs_drilldown.php?select_make={$row['ManufacturerName']}'>{$row['ManufacturerName']}</a>" : "{$row['ManufacturerName']}")."</td>";
										print "<td>{$row['RepairCount']}</td>";
										print "<td>{$row['TotalPartsCost']}</td>";
										print "<td>{$row['TotalLaborCharges']}</td>";
										print "<td>{$row['TotalRepairCost']}</td>";
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