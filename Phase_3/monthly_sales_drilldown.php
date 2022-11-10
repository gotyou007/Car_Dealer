<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$select_year = $_GET['select_year'];
$select_month = $_GET['select_month'];

include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Top Salesperson</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Top Salesperson</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">First name</td>
									<td class="heading">Last name</td>
									<td class="heading">Total vehicle sold</td>
									<td class="heading">Gross sales income</td>
								</tr>
								<?php
									$query = is_null($select_month) ? get_sales_drilldown_by_year($select_year) : get_sales_drilldown_by_month($select_year, $select_month);
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['FirstName']}</td>";
										print "<td>{$row['LastName']}</td>";
										print "<td>{$row['TotalVehiclesSold']}</td>";
										print "<td>{$row['TotalSales']}</td>";
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