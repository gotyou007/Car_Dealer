<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by dxu329

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

$query = $parts_stats_sql;
         
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
?>

<?php include("lib/header.php"); ?>
		<title>Sales by Type</title>
	</head>
	
	<body>
        <div id="main_container">
		    <?php include("lib/menu.php"); ?>
            
			<div class="center_content">
				<div class="center_left">					
					<div class="features">   	
						<div class="profile_section">
                        	<div class="subtitle">Parts Statistics</div>   
							
							<table>
								<tr>
									<td class="heading">Vendor name</td>
									<td class="heading">Number of parts</td>
									<td class="heading">Total spent</td>
								</tr>
								<?php
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['VendorName']}</td>";
										print "<td>{$row['NumberOfParts']}</td>";
										print "<td>{$row['TotalSpent']}</td>";
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