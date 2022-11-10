<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Sales Summary</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Sales By Year</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">Year</td>
									<td class="heading">Number sold</td>
									<td class="heading">Gross income</td>
									<td class="heading">Net income</td>
									<td class="heading">Sold/Invoice ratio</td>
								</tr>
								<?php
									$query = $sales_by_year_sql;
									
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print $row['ProfitRatio'] >= 125 ? "<tr style='background-color: green;'>" : ($row['ProfitRatio'] <= 110 ? "<tr style='background-color: yellow;'>" : "<tr>");
										print "<td><a href='monthly_sales_drilldown.php?select_year={$row['Year']}'>{$row['Year']}</a></td>";
										print "<td>{$row['TotalVehiclesSold']}</td>";
										print "<td>{$row['TotalSalesIncome']}</td>";
										print "<td>{$row['TotalNetIncome']}</td>";
										print "<td>{$row['ProfitRatio']}%</td>";
										print "</tr>";							
									}									
								?>
							</table>						
						</div>
						<div class="profile_section">
                        	<div class="subtitle">Sales By Year&Month</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">Year</td>
									<td class="heading">Month</td>
									<td class="heading">Number sold</td>
									<td class="heading">Gross income</td>
									<td class="heading">Net income</td>
									<td class="heading">Sold/Invoice ratio</td>
								</tr>
								<?php
									$query = $sales_by_year_month_sql;
									
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print $row['ProfitRatio'] >= 125 ? "<tr style='background-color: green;'>" : ($row['ProfitRatio'] <= 110 ? "<tr style='background-color: yellow;'>" : "<tr>");
										print "<td>{$row['Year']}</td>";
										print "<td><a href='monthly_sales_drilldown.php?select_year={$row['Year']}&select_month={$row['Month']}'>{$row['Month']}</a></td>";
										print "<td>{$row['TotalVehiclesSold']}</td>";
										print "<td>{$row['TotalSalesIncome']}</td>";
										print "<td>{$row['TotalNetIncome']}</td>";
										print "<td>{$row['ProfitRatio']}%</td>";
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