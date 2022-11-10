<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$select_id = $_GET['select_id'];

include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Customer Transactions</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Sales Transactions</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">Date</td>
									<td class="heading">Price</td>
									<td class="heading">VIN</td>
									<td class="heading">Year</td>
									<td class="heading">Manufacturer</td>
                                    <td class="heading">Model</td>
                                    <td class="heading">Salesperson name</td>
								</tr>
								<?php
									$query = get_income_drilldown_by_sales($select_id);
									
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['DateSold']}</td>";
										print "<td>{$row['SoldPrice']}</td>";
										print "<td>{$row['VIN']}</td>";
										print "<td>{$row['ModelYear']}</td>";
										print "<td>{$row['ManufacturerName']}</td>";
                                        print "<td>{$row['ModelName']}</td>";
                                        print "<td>{$row['SalespeopleName']}</td>";
										print "</tr>";							
									}									
								?>
							</table>						
						</div>
                        <div class="profile_section">
                        	<div class="subtitle">Repair Transactions</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">Start date</td>
                                    <td class="heading">End date</td>
									<td class="heading">VIN</td>
									<td class="heading">Odometer</td>
									<td class="heading">Parts cost</td>
                                    <td class="heading">Labor cost</td>
                                    <td class="heading">Total cost</td>
                                    <td class="heading">Service writer name</td>
								</tr>
								<?php
									$query = get_income_drilldown_by_repairs($select_id);
									
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['StartDate']}</td>";
										print "<td>{$row['EndDate']}</td>";
										print "<td>{$row['VIN']}</td>";
										print "<td>{$row['Odometer']}</td>";
										print "<td>{$row['PartsCost']}</td>";
                                        print "<td>{$row['LaborCharges']}</td>";
                                        print "<td>{$row['TotalCost']}</td>";
                                        print "<td>{$row['ServiceWriterName']}</td>";
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