<?php

include('lib/common.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

?>

<?php include("lib/header.php"); ?>
		<title>Reports</title>
	</head>
	
	<body>
    	<div id="main_container">
            <?php include("lib/menu.php"); ?>
			
			<div class="center_content">
				<div class="center_left">
					<div class="features">  
						<div class='subtitle'>Report Types</div>
                        <a href='sales_by_color.php'>Sales by Color</a><br><br>
                        <a href='sales_by_type.php'>Sales by Type</a><br><br>
                        <a href='sales_by_manufacturer.php'>Sales by Manufacturer</a><br><br>
                        <a href='gross_customer_income.php'>Gross Customer Income</a><br><br>
                        <a href='repairs_by_MTM.php'>Repairs by Manufacturer/Type/Model</a><br><br>
                        <a href='below_cost_sales.php'>Below Cost Sales</a><br><br>
                        <a href='average_inventory_time.php'>Average Time in Inventory</a><br><br>
                        <a href='parts_stats.php'>Parts Statistics</a><br><br>
                        <a href='monthly_sales.php'>Monthly Sales</a><br><br>
					 </div> 
				</div> 
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    
            
               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>
</html>