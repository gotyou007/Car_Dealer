<?php

include('lib/common.php');
include('lib/sqlLib.php');


/* if form was submitted, then execute query to search for vehicles */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $VehicleType = mysqli_real_escape_string($db, $_POST['VehicleType']);
    $ManufacturerName = mysqli_real_escape_string($db, $_POST['ManufacturerName']);
    if(!empty('$ListPrice') AND is_numeric($_POST['ListPrice']))
    {
        //typecast to a float
        $ListPrice  = (float) $_POST['ListPrice'];}

    $ModelYear  = (int) $_POST['ModelYear'];


    $ColorName = mysqli_real_escape_string($db, $_POST['ColorName']);
    $Keyword = mysqli_real_escape_string($db, $_POST['Keyword']);
    $Availability = mysqli_real_escape_string($db, $_POST['Availability']);

    $ModelYearRange = $search_vehicle_get_manufacturer;
    $ModelYearResult = mysqli_query($db, $ModelYearRange);
    $ModelYearRow = mysqli_fetch_array($ModelYearResult, MYSQLI_ASSOC);

    
    $formError = false;
    if (!empty($ModelYear)) {
        if ($ModelYear > $ModelYearRow["ModelYear"] + 1) {
            $formError = true;
            array_push($error_msg,  "SELECT ERROR: Model Years cannot exceed the current year plus one <br>" . __FILE__ ." line:". __LINE__ );
        } else if ($ModelYear > 9999 or $ModelYear < 1000) {
            $formError = true;
            array_push($error_msg,  "SELECT ERROR: Invalid Model Year <br>" . __FILE__ ." line:". __LINE__ );
        }
    }

    if (!$formError) {
        $query = get_search_vehicle_query($VehicleType, $ManufacturerName, $ModelYear, $ColorName, $ListPrice, $Keyword, $Availability);

        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
    
        if (mysqli_num_rows($result) === 0) {
            array_push($error_msg,  "SELECT ERROR:Sorry, it looks like we don't have that in stock! <br>");
        }
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

                    <form name="searchform" action="search_vehicle.php" method="POST">
                        <table>
                            <tr>
                                <td class="item_label">Manufacturer</td>
                                <td>
                                    <select name="ManufacturerName">
                                        <option disabled selected value> -- select an option -- </option>
                                        <?php
                                        $ManufacturerList = $db ->query($get_all_manufacturers_sql);
                                        foreach($ManufacturerList as $i) {
                                            ?>
                                            <option value="<?php echo $i["ManufacturerName"]; ?>"><?php echo $i["ManufacturerName"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>



                                <td class="item_label">Vehicle Type</td>
                                <td>
                                    <select name="VehicleType">
                                        <option disabled selected value> -- select an option -- </option>
                                        <option value="Car">Car</option>
                                        <option value="Truck">Truck</option>
                                        <option value="SUV">SUV</option>
                                        <option value="Convertible">Convertible</option>
                                        <option value="Van">Van</option>

                                    </select>
                                </td>


                                <td class="item_label">Model Year</td>
                                <td><input name="ModelYear" type="number"/></td>
                            </tr>
                            <tr>
                            <td class="item_label">Color</td>
                                <td>
                                    <select name="ColorName">
                                        <option disabled selected value> -- select an option -- </option>
                                        <?php
                                        $ColorList = $db ->query($get_all_colors_sql);
                                        foreach($ColorList as $i) {
                                            if ($i["ColorName"] != "Multiple") {
                                            ?>

                                            <option value="<?php echo $i["ColorName"]; ?>"><?php echo $i["ColorName"]; ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="item_label">Max Price</td>
                                <td><input type="number" name="ListPrice" /></td>

                                <td class="item_label">Keyword</td>
                                <td><input type="text" name="Keyword" /></td>
                                
                            </tr>
                            <?php if($_SESSION['type'] === "Manager" || $_SESSION['type'] === "Owner"){ ?>
                                <tr>
                                    <td class="item_label">Availability</td>
                                    <td>
                                        <select name="Availability">
                                            <option disabled selected value> -- select an option -- </option>
                                            <option value="Sold">Sold</option>
                                            <option value="Unsold">Unsold (default)</option>
                                            <option value="All">All</option>
                                        </select>
                                    </td>
                                <tr>    
                            <?php } ?>
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
                        function highlightKeywords($text, $keyword) {
                            $wordsAry = explode(" ", $keyword);
                            $wordsCount = count($wordsAry);

                            for($i=0;$i<$wordsCount;$i++) {
                                $highlighted_text = "<span style='font-weight:bold;'>$wordsAry[$i]</span>";
                                $text = str_ireplace($wordsAry[$i], $highlighted_text, $text);
                            }

                            return $text;
                        }
                        if (isset($result)) {
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                $VIN = urlencode($row['VIN']);
                                print "<tr>";


                                print "<td><a href='view_vehicle.php?VIN=$VIN'>{$row['VIN']}</a></td>";
                                print "<td>{$row['VehicleType']}</td>";
                                $ModelYear = highlightKeywords($row['ModelYear'], $Keyword);
                                print "<td>{$ModelYear}</td>";
                                $ManufacturerName = highlightKeywords($row['ManufacturerName'], $Keyword);
                                print "<td>{$ManufacturerName}</td>";
                                $ModelName = highlightKeywords($row['ModelName'], $Keyword);
                                print "<td>{$ModelName}</td>";
                                print "<td>{$row['Colors']}</td>";
                                print "<td>{$row['ListPrice']}</td>";
                                $Description = highlightKeywords($row['Description'], $Keyword);
                                print "<td>{$Description}</td>";

                                print "</tr>";

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