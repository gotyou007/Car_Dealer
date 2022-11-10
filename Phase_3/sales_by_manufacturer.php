<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = $sales_by_manufacturer_sql;

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Sales by Manufacturer</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Sales by Manufacturer</div>   
							
							<table>
								<tr>
									<td class="heading">Manufacturer</td>
									<td class="heading">All time</td>
									<td class="heading">Previous year</td>
									<td class="heading">Previous 30 days</td>
								</tr>
								<?php
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['ManufacturerName']}</td>";
										print "<td>{$row['AllTime']}</td>";
										print "<td>{$row['PrevYear']}</td>";
										print "<td>{$row['PrevMonth']}</td>";
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