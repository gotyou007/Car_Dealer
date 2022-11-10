<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = $average_inventory_time_sql;

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Average Time in Inventory</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Average Time in Inventory</div>   
							
							<table>
								<tr>
									<td class="heading">Vehicle type</td>
									<td class="heading">Average day count</td>
								</tr>
								<?php
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['VehicleType']}</td>";
										print "<td>".($row['Days'] ?: 'N/A')."</td>";
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