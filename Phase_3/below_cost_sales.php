<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = $below_cost_sql;

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Below Cost Sales</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Below Cost Sales</div>   
							
							<table style="width: 200%;">
								<tr>
									<td class="heading">Date</td>
									<td class="heading">Invoice price</td>
									<td class="heading">Sold price</td>
									<td class="heading">Sold/Invoice ratio</td>
									<td class="heading">Customer</td>
									<td class="heading">Salesperson</td>
								</tr>
								<?php
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print $row['ProfitRatio'] > 95 ? "<tr>" : "<tr style='background-color: red;'>";
										print "<td>{$row['DateSold']}</td>";
										print "<td>{$row['InvoicePrice']}</td>";
										print "<td>{$row['SoldPrice']}</td>";
										print "<td>{$row['ProfitRatio']}%</td>";
										print "<td>{$row['CustomerName']}</td>";
										print "<td>{$row['SalespersonName']}</td>";
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