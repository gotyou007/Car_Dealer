<?php
include('lib/common.php');
include('lib/sqlLib.php');

/* if form was submitted, then execute query to search for vehicles */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $InputVIN = mysqli_real_escape_string($db, $_POST['VIN']);
    $query = search_by_vin($InputVIN);

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');

    if (mysqli_num_rows($result) === 0) {
        array_push($error_msg, "SELECT ERROR:Sorry, it looks like we don't have that in stock! <br>");
    }
}
?>

<?php include("lib/header.php"); ?>
<title>Jaunty Vehicle Search</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Search for Vehicles</div>

                    <form name="searchform" action="search_by_VIN.php" method="POST">
                        <table>
                            <tr>
                                <td class="item_label">VIN</td>
                                <td><input type="text" name="VIN" /></td>
                            </tr>


                        </table>
                        <a href="javascript:searchform.submit();" class="fancy_button">Search</a>
                    </form>
                </div>

                <div class='profile_section'>
                    <div class='subtitle'>Search Results</div>
                    <table>
                        <tr>
                            <td class='heading'>VIN</td>
                            <td class='heading'>VehicleType</td>
                            <td class='heading'>Model Year</td>
                            <td class='heading'>Manufacturer</td>
                            <td class='heading'>Model</td>
                            <td class='heading'>Colors</td>
                            <td class='heading'>ListPrice</td>
                            <td class='heading'>Description</td>

                        </tr>
                        <?php
                        if (isset($result)) {
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        $VIN = urlencode($row['VIN']);
                        print "<tr>";


                        print "<td><a href='view_vehicle.php?VIN=$VIN'>{$row['VIN']}</a></td>";
                        print "<td>{$row['VehicleType']}</td>";
                        print "<td>{$row['ModelYear']}</td>";
                        print "<td>{$row['ManufacturerName']}</td>";
                        print "<td>{$row['ModelName']}</td>";
                        print "<td>{$row['Colors']}</td>";
                        print "<td>{$row['ListPrice']}</td>";
                        print "<td>{$row['Description']}</td>";

                        print "</tr>";
                        }
                        }	?>
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
