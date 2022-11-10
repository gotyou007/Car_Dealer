<?php

include('lib/common.php');
include('lib/sqlLib.php');
// written by GTusername4

	$query = "SELECT COUNT(*) NUM FROM Vehicle WHERE DateSold IS NULL";
	$result = mysqli_query($db, $query);
	if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }

?>



<?php include("lib/header.php"); ?>
<title>Jaunty Auto</title>
</head>

<body>
    <div id="main_container">
        <?php include("lib/menu.php"); ?>

        <div class="center_content">
            <div class="title_name">Vehicle Available</div>
            <div class="title_name">
                <?php print $row['NUM']; ?>
            </div>

            </div>
        </div>
    </div>
</body>
</html>