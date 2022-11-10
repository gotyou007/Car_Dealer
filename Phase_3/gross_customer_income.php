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
		<title>Gross Customer Income</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Gross Customer Income</div>
							<table style="width: 200%;">
								<tr>
									<td class="heading">Customer name</td>
									<td class="heading">Total income</td>
									<td class="heading">First transaction date</td>
									<td class="heading">Last transaction date</td>
									<td class="heading">Number of sales</td>
                                    <td class="heading">Number of repairs</td>
								</tr>
								<?php
									$query = $gross_income_sql;
									
									$result = mysqli_query($db, $query);
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td><a href='gross_income_drilldown.php?select_id={$row['CustomerID']}'>{$row['CustomerName']}</a></td>";
										print "<td>{$row['TotalIncome']}</td>";
										print "<td>{$row['FirstTransactionDate']}</td>";
										print "<td>{$row['LastTransactionDate']}</td>";
										print "<td>{$row['NumberOfSales']}</td>";
                                        print "<td>{$row['NumberOfRepairs']}</td>";
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