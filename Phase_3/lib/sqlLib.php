<?php
// Login
function try_login_as_user($Email) {
    return "SELECT * FROM User WHERE Username='$Email'";
}

// Add customer
function add_customer_insert_customer ($email, $street, $city, $state, $zipcode, $phone) {
    $query = "INSERT INTO Customer (Email, StreetAddress, City, State, ZipCode, Phone)".
             "VALUES ('$email', '$street', '$city', '$state', '$zipcode', '$phone');";

    return $query;
}

$add_customer_fetch_customerid = "SELECT CustomerID ".
                                   "FROM Customer ".
                                   "ORDER BY CustomerID DESC LIMIT 1; ";

function add_customer_insert_individual ($driverlicense, $customerid, $firstname, $lastname) {
    $query = "INSERT INTO Individual (DriverLicense, CustomerID, FirstName, LastName) ".
              "VALUES ('$driverlicense', '$customerid', '$firstname', '$lastname');";

    return $query;
}

function add_customer_insert_business ($businessid, $customerid, $businessname, $primaryname, $primarytitle) {
     $query = "INSERT INTO Business (TaxID, CustomerID, BusinessName, PrimaryContactName, PrimaryContactTitle) ".
               "VALUES ('$businessid', '$customerid', '$businessname', '$primaryname', '$primarytitle');";

     return $query;
 }

// Add vehicle
$add_vehicle_pull_manufacturer = 
"SELECT ManufacturerName FROM Manufacturer";

function add_vehicle_insert_vehicle ($vin, $manufacturer, $model, $model_year, $invoice_price, $description, $email, $date_added, $colors) {
    $query = "INSERT INTO Vehicle(VIN, ManufacturerName, ModelName, ModelYear, InvoicePrice, Description, ClerkUserName, DateAdded) " .
        "VALUES ('$vin', '$manufacturer', '$model', '$model_year', '$invoice_price', '$description', '$email', '$date_added');";

    $color_array = explode(",", $colors);
    foreach($color_array as $color) {
        $query .= "INSERT INTO Vehicle_Color(VIN, ColorName) " .
                "Values ('$vin', '$color');";
    }

    return $query;
}

function add_vehicle_insert_type ($vehicle_type, $vin, $door_count, $roof_type, $back_seat_count, $cargo_capacity, $cargo_cover_type, $rear_axis_count, $driver_side_door, $drive_train_type, $cup_holder_count) {
    $query = "";
    if ($vehicle_type == 'Car') {
        $query .= "INSERT INTO Vehicle_Car(VIN, DoorCount) VALUES ('$vin', '$door_count')";
    } elseif ($vehicle_type == 'Convertible') {
        $query .= "INSERT INTO Vehicle_Convertible(VIN, RoofType, BackSeatCount) VALUES ('$vin', '$roof_type', '$back_seat_count')";
    } elseif ($vehicle_type == 'Truck') {
        $query .= "INSERT INTO Vehicle_Truck(VIN, CargoCapacity, CargoCoverType, RearAxisCount) VALUES ('$vin', '$cargo_capacity', '$cargo_cover_type', '$rear_axis_count')";
    } elseif ($vehicle_type == 'Van/Minivan') {
        if ($driver_side_door == 'yes') {
            $boolean_driver_side_door = true;
        } else {
            $boolean_driver_side_door = false;
        }
        $query .= "INSERT INTO Vehicle_Van(VIN, DriverSideDoor) VALUES ('$vin', '$boolean_driver_side_door')";
    } elseif ($vehicle_type == 'SUV') {
        $query .= "INSERT INTO Vehicle_SUV(VIN, DriveTrainType, CupHolderCount) VALUES ('$vin', '$drive_train_type', '$cup_holder_count')";
    }
    return $query;
}

// sell vehicle & create repair
function search_business ($ID) {
    $query = "SELECT CustomerID, BusinessName ".
               "FROM Business ".
               "WHERE Business.TaxID='$ID'";

    return $query;
}

function search_individual ($ID) {
    $query = "SELECT CustomerID, CONCAT(FirstName, ' ', LastName) as IndividualName ".
              "FROM Individual ".
              "WHERE Individual.DriverLicense='$ID'";

    return $query;
}

function create_repair ($vin, $startdate, $customerid, $odometer, $description, $email) {
     $query = "INSERT INTO Repair(VIN, StartDate, CustomerID, Odometer, LaborCharges, Description, ServiceWriterUsername) ".
               "VALUES ('$vin', '$startdate', '$customerid', '$odometer', 0, '$description', '$email')";

     return $query;
}

function find_invoice_price_by_vin ($vin) {
      $query = "SELECT InvoicePrice ".
                "FROM Vehicle ".
                "WHERE VIN = '$vin';";

      return $query;
}

function sell_vehicle ($customerid, $soldprice, $solddate, $email, $vin) {
      $query = "UPDATE Vehicle ".
                "SET CustomerID='$customerid', SoldPrice='$soldprice', DateSold='$solddate', SalespeopleUsername='$email' ".
                "WHERE VIN = '$vin';";

      return $query;
}

// View repairs
function view_repair_pull_vehicle ($VIN) {
    return "SELECT Vehicle.VIN, ModelYear,ModelName, DateSold,Vehicle.ManufacturerName, (CASE
            WHEN DoorCount IS NOT NULL THEN 'Car'
            WHEN CargoCapacity IS NOT NULL THEN 'Truck'
            WHEN RoofType IS NOT NULL THEN 'Convertible'
            WHEN DriveTrainType IS NOT NULL THEN 'SUV'
            WHEN DriverSideDoor IS NOT NULL THEN 'Van'
            END) AS VehicleType,
            GROUP_CONCAT(ColorName) AS Colors
            FROM Vehicle
            JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
            LEFT JOIN Vehicle_Car ON Vehicle.VIN = Vehicle_Car.VIN
            LEFT JOIN Vehicle_Truck ON Vehicle.VIN = Vehicle_Truck .VIN
            LEFT JOIN Vehicle_SUV ON Vehicle.VIN = Vehicle_SUV.VIN
            LEFT JOIN Vehicle_Convertible ON Vehicle.VIN = Vehicle_Convertible.VIN
            LEFT JOIN Vehicle_Van ON Vehicle.VIN = Vehicle_Van.VIN
            WHERE Vehicle.VIN='$VIN'";
}

function view_repair_pull_repair ($VIN) {
    return "SELECT VIN FROM Repair WHERE Repair.VIN='$VIN' AND Repair.EndDate IS NULL;";
}

function view_repair_pull_repair_open ($VIN) {
    return "SELECT VIN, StartDate, LaborCharges, EndDate FROM Repair WHERE Repair.VIN='$VIN' ORDER BY StartDate DESC;";
}


// update repair
function update_repair_pull_repair ($VIN, $StartDate) {
    return "SELECT VIN, StartDate, LaborCharges, Description, EndDate FROM Repair WHERE Repair.VIN='$VIN' and Repair.StartDate='$StartDate'";
}

function update_repair_insert_part($VIN, $StartDate, $part_number, $quantity_used, $unit_price, $vendor_name) {
    return "INSERT INTO Part(VIN, StartDate, PartNumber, QuantityUsed, UnitPrice, VendorName) VALUES ('$VIN', '$StartDate', '$part_number', '$quantity_used', '$unit_price', '$vendor_name');";
}

function update_repair_update_labor_charges_description ($labor_charges, $description, $VIN, $StartDate) {
    return "UPDATE Repair SET LaborCharges='$labor_charges', `Description`='$description' WHERE VIN='$VIN' AND StartDate='$StartDate';";
}

function update_repair_update_completion($date_added, $VIN, $StartDate) {
    return "UPDATE Repair SET EndDate='$date_added' WHERE VIN='$VIN' AND StartDate='$StartDate'";
}

function update_repair_pull_parts($VIN, $StartDate) {
    return "SELECT VIN, StartDate, PartNumber, QuantityUsed, UnitPrice, VendorName FROM Part WHERE VIN='$VIN' AND StartDate='$StartDate';";
}
// Reports
$sales_by_color_sql =
"SELECT tmp1.ColorName, COALESCE(ALLTIME,0) AS AllTime, COALESCE(YEARCOUNT,0) AS PrevYear, COALESCE(DAYCOUNT,0) AS PrevMonth FROM
(
  SELECT ColorName, COUNT(VIN) AS ALLTIME
  FROM Color
  LEFT JOIN
  (
    SELECT Vehicle.VIN,(CASE
    WHEN GROUP_CONCAT(Vehicle_Color.ColorName) LIKE '%,%' THEN 'Multiple'
    ELSE GROUP_CONCAT(Vehicle_Color.ColorName)
    END) AS ColorType, DateSold
    FROM Vehicle
    LEFT JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
    WHERE Vehicle.CustomerID IS NOT NULL 
    GROUP BY Vehicle.VIN
  ) colormulti1 ON Color.ColorName = colormulti1.ColorType
  GROUP BY ColorName
) tmp1
LEFT JOIN
(
  SELECT ColorName, COUNT(VIN) AS YEARCOUNT
  FROM Color
  LEFT JOIN
  (SELECT Vehicle.VIN,(CASE
    WHEN GROUP_CONCAT(Vehicle_Color.ColorName) LIKE '%,%' THEN 'Multiple'
    ELSE GROUP_CONCAT(Vehicle_Color.ColorName)
    END) AS ColorType, DateSold
    FROM Vehicle
    LEFT JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
    WHERE Vehicle.CustomerID IS NOT NULL 
    GROUP BY Vehicle.VIN) colormulti2 ON Color.ColorName = colormulti2.ColorType
  WHERE DATEDIFF(NOW(), DateSold-1) < 365
  GROUP BY ColorName
) tmp2  ON tmp1.ColorName = tmp2.ColorName
LEFT JOIN
(
  SELECT ColorName, COUNT(VIN) AS DAYCOUNT
  FROM Color
  LEFT JOIN
  (SELECT Vehicle.VIN,(CASE
    WHEN GROUP_CONCAT(Vehicle_Color.ColorName) LIKE '%,%' THEN 'Multiple'
    ELSE GROUP_CONCAT(Vehicle_Color.ColorName)
    END) AS ColorType, DateSold
    FROM Vehicle
    LEFT JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
    WHERE Vehicle.CustomerID IS NOT NULL 
    GROUP BY Vehicle.VIN) colormulti3 ON Color.ColorName = colormulti3.ColorType
  WHERE DATEDIFF(NOW(), DateSold-1) < 30
  GROUP BY ColorName
) tmp3 ON tmp1.ColorName = tmp3.ColorName
ORDER BY ColorName";

$sales_by_type_sql =
"SELECT tmp1.VehicleType, COALESCE(ALLTIME,0) AS AllTime, COALESCE(YEARCOUNT,0) AS PrevYear, COALESCE(DAYCOUNT,0) AS PrevMonth FROM
(SELECT  
(CASE
  WHEN DoorCount IS NOT NULL THEN 'Car'
  WHEN CargoCapacity IS NOT NULL THEN 'Truck'
  WHEN RoofType IS NOT NULL THEN 'Convertible'
  WHEN DriveTrainType IS NOT NULL THEN 'SUV'
  WHEN DriverSideDoor IS NOT NULL THEN 'Van'
  END) AS VehicleType, COUNT(v.VIN) as ALLTIME
FROM Vehicle v
LEFT JOIN Vehicle_Car ON v.VIN = Vehicle_Car.VIN
LEFT JOIN Vehicle_Truck ON v.VIN = Vehicle_Truck .VIN
LEFT JOIN Vehicle_SUV ON v.VIN = Vehicle_SUV.VIN
LEFT JOIN Vehicle_Convertible ON v.VIN = Vehicle_Convertible.VIN
LEFT JOIN Vehicle_Van ON v.VIN = Vehicle_Van.VIN
WHERE v.CustomerID IS NOT NULL 
GROUP BY VehicleType
) tmp1
LEFT JOIN
(SELECT  
(CASE
  WHEN DoorCount IS NOT NULL THEN 'Car'
  WHEN CargoCapacity IS NOT NULL THEN 'Truck'
  WHEN RoofType IS NOT NULL THEN 'Convertible'
  WHEN DriveTrainType IS NOT NULL THEN 'SUV'
  WHEN DriverSideDoor IS NOT NULL THEN 'Van'
  END) AS VehicleType, COUNT(v.VIN) as YEARCOUNT
FROM Vehicle v
LEFT JOIN Vehicle_Car ON v.VIN = Vehicle_Car.VIN
LEFT JOIN Vehicle_Truck ON v.VIN = Vehicle_Truck .VIN
LEFT JOIN Vehicle_SUV ON v.VIN = Vehicle_SUV.VIN
LEFT JOIN Vehicle_Convertible ON v.VIN = Vehicle_Convertible.VIN
LEFT JOIN Vehicle_Van ON v.VIN = Vehicle_Van.VIN
WHERE v.CustomerID IS NOT NULL AND DATEDIFF(NOW(), DateSold-1) < 365
GROUP BY VehicleType) tmp2 ON tmp1.VehicleType = tmp2.VehicleType
LEFT JOIN
(SELECT  
(CASE
  WHEN DoorCount IS NOT NULL THEN 'Car'
  WHEN CargoCapacity IS NOT NULL THEN 'Truck'
  WHEN RoofType IS NOT NULL THEN 'Convertible'
  WHEN DriveTrainType IS NOT NULL THEN 'SUV'
  WHEN DriverSideDoor IS NOT NULL THEN 'Van'
  END) AS VehicleType, COUNT(v.VIN) as DAYCOUNT
FROM Vehicle v
LEFT JOIN Vehicle_Car ON v.VIN = Vehicle_Car.VIN
LEFT JOIN Vehicle_Truck ON v.VIN = Vehicle_Truck .VIN
LEFT JOIN Vehicle_SUV ON v.VIN = Vehicle_SUV.VIN
LEFT JOIN Vehicle_Convertible ON v.VIN = Vehicle_Convertible.VIN
LEFT JOIN Vehicle_Van ON v.VIN = Vehicle_Van.VIN
WHERE v.CustomerID IS NOT NULL AND DATEDIFF(NOW(), DateSold-1) < 30
GROUP BY VehicleType
) tmp3 ON tmp1.VehicleType = tmp3.VehicleType";

$sales_by_manufacturer_sql =
"SELECT tmp1.ManufacturerName, COALESCE(ALLTIME,0) AS AllTime, COALESCE(YEARCOUNT,0) AS PrevYear, COALESCE(DAYCOUNT,0) AS PrevMonth FROM
(SELECT ManufacturerName, COUNT(Vehicle.VIN) as ALLTIME
FROM Vehicle
WHERE Vehicle.CustomerID IS NOT NULL 
GROUP BY ManufacturerName
) tmp1
LEFT JOIN
(SELECT ManufacturerName, COUNT(Vehicle.VIN) as YEARCOUNT
FROM Vehicle
WHERE Vehicle.CustomerID IS NOT NULL AND DATEDIFF(NOW(), DateSold-1) < 365
GROUP BY ManufacturerName) tmp2 ON tmp1.ManufacturerName = tmp2.ManufacturerName
LEFT JOIN
(SELECT ManufacturerName, COUNT(Vehicle.VIN) as DAYCOUNT
FROM Vehicle
WHERE Vehicle.CustomerID IS NOT NULL AND DATEDIFF(NOW(), DateSold-1) < 30
GROUP BY ManufacturerName) tmp3 ON tmp1.ManufacturerName = tmp3.ManufacturerName";

$gross_income_sql =
"SELECT 
COALESCE(CONCAT(i.FirstName, ' ',i.LastName), BusinessName) AS CustomerName,
srg.CustomerID, srg.TotalIncome, srg.FirstTransactionDate, srg.LastTransactionDate, srg.NumberOfSales, srg.NumberOfRepairs
FROM (SELECT
    sr.CustomerID,
    MIN(FirstTransactionDate) AS FirstTransactionDate, 
    MAX(LastTransactionDate) AS LastTransactionDate, 
    SUM(NumberOfSales) NumberOfSales,
    SUM(NumberOfRepairs) AS NumberOfRepairs, 
    ROUND(SUM(TotalIncome), 2) AS TotalIncome
    FROM (SELECT CustomerID,
            SUM(SoldPrice) AS TotalIncome,
            COUNT(*) AS NumberOfSales,
            0 AS NumberOfRepairs,
            MIN(DateSold) AS FirstTransactionDate,
            MAX(DateSold) AS LastTransactionDate
            FROM (SELECT CustomerID, DateSold, SoldPrice
                FROM Vehicle v
                WHERE CustomerID IS NOT NULL) sales_income
            GROUP BY CustomerID
            UNION
            SELECT CustomerID,
            SUM(LaborCharges+PartsCost) AS TotalIncome,
            0 AS NumberOfSales,
            COUNT(*) AS NumberOfRepairs,
            MIN(StartDate) AS FirstTransactionDate,
            MAX(StartDate) AS LastTransactionDate
            FROM (SELECT CustomerID, r.StartDate, LaborCharges, 
                SUM(COALESCE((UnitPrice*QuantityUsed), 0)) AS PartsCost
                FROM Repair r
                LEFT JOIN Part p ON r.VIN = p.VIN AND r.StartDate = p.StartDate
                GROUP BY r.VIN, r.StartDate) repair_income
            GROUP BY CustomerID) sr
    GROUP BY CustomerID) srg
LEFT JOIN Individual i ON srg.CustomerID = i.CustomerID
LEFT JOIN Business b ON srg.CustomerID = b.CustomerID
ORDER BY TotalIncome DESC, LastTransactionDate DESC
LIMIT 15";

function get_income_drilldown_by_sales($id) {
    return "SELECT DateSold, SoldPrice, VIN, ModelYear, ManufacturerName, ModelName, SalespeopleName
            FROM (SELECT CustomerID, DateSold, SoldPrice, VIN, ModelYear, ManufacturerName, ModelName,
                CONCAT(u.FirstName,' ',u.LastName) AS SalespeopleName
                FROM Vehicle v
                JOIN User u ON v.SalespeopleUsername = u.Username
                WHERE CustomerID IS NOT NULL) sales_income
            WHERE CustomerID = $id
            ORDER BY DateSold DESC, VIN";
}

function get_income_drilldown_by_repairs($id) {
    return "SELECT StartDate, EndDate, VIN, Odometer, PartsCost, LaborCharges, ROUND(PartsCost+LaborCharges, 2) AS TotalCost, ServiceWriterName
            FROM (SELECT CustomerID, r.StartDate, COALESCE(EndDate, '') AS EndDate, r.VIN, Odometer, LaborCharges, 
                ROUND(SUM(COALESCE((UnitPrice*QuantityUsed), 0)), 2) AS PartsCost, CONCAT(u.FirstName,' ',u.LastName) AS ServiceWriterName
                FROM Repair r
                LEFT JOIN Part p ON r.VIN = p.VIN AND r.StartDate = p.StartDate
                JOIN User u ON r.ServiceWriterUsername = u.Username
                GROUP BY r.VIN, r.StartDate) repair_income
            WHERE CustomerID = $id
            ORDER BY EndDate IS NOT NULL, StartDate DESC, EndDate DESC, VIN";
}

$repair_by_manufacturer_sql =
"SELECT
ManufacturerName,
SUM(StartDate IS NOT NULL) AS RepairCount,
COALESCE(SUM(PartsCost), 0.00) AS TotalPartsCost,
ROUND(SUM(LaborCharges), 2) AS TotalLaborCharges,
ROUND(SUM(RepairCost), 2) AS TotalRepairCost
FROM (SELECT v.VIN, ModelName, m.ManufacturerName, StartDate,
      COALESCE(LaborCharges, 0) AS LaborCharges,
      PartsCost,
      COALESCE(LaborCharges+PartsCost, 0) AS RepairCost
      FROM Vehicle v
      RIGHT JOIN
      Manufacturer m ON v.ManufacturerName = m.ManufacturerName
      LEFT JOIN 
      (SELECT r.VIN, r.StartDate, r.LaborCharges,
	   ROUND(SUM(COALESCE(UnitPrice*QuantityUsed, 0)), 2) AS PartsCost 
       FROM Repair r
       LEFT JOIN Part p ON r.VIN = p.VIN AND r.StartDate = p.StartDate
       GROUP BY r.VIN, r.StartDate) rp
       ON v.VIN = rp.VIN) r
GROUP BY ManufacturerName
ORDER BY ManufacturerName";

function get_repair_by_make_sql($make) {
    return "SELECT t.VehicleType,
            COUNT(StartDate) AS RepairCount,
            ROUND(SUM(PartsCost), 2) AS TotalPartsCost,
            ROUND(SUM(LaborCharges), 2) AS TotalLaborCharges,
            ROUND(SUM(RepairCost), 2) AS TotalRepairCost
            FROM (SELECT v.VIN, ModelName, ManufacturerName, StartDate,
                COALESCE(LaborCharges, 0) AS LaborCharges,
                PartsCost,
                COALESCE(LaborCharges+PartsCost, 0) AS RepairCost
                FROM Vehicle v
                LEFT JOIN 
                (SELECT r.VIN, r.StartDate, r.LaborCharges,
                SUM(COALESCE(UnitPrice*QuantityUsed, 0)) AS PartsCost 
                FROM Repair r
                LEFT JOIN Part p ON r.VIN = p.VIN AND r.StartDate = p.StartDate
                GROUP BY r.VIN, r.StartDate) rp
                ON v.VIN = rp.VIN) r
            LEFT JOIN (SELECT v.VIN,
                (CASE
                    WHEN DoorCount IS NOT NULL THEN 'Car'
                    WHEN CargoCapacity IS NOT NULL THEN 'Truck'
                    WHEN RoofType IS NOT NULL THEN 'Convertible'
                    WHEN DriveTrainType IS NOT NULL THEN 'SUV'
                    WHEN DriverSideDoor IS NOT NULL THEN 'Van'
                    END) AS VehicleType
                FROM Vehicle v
                LEFT JOIN Vehicle_Car ON v.VIN = Vehicle_Car.VIN
                LEFT JOIN Vehicle_Truck ON v.VIN = Vehicle_Truck .VIN
                LEFT JOIN Vehicle_SUV ON v.VIN = Vehicle_SUV.VIN
                LEFT JOIN Vehicle_Convertible ON v.VIN = Vehicle_Convertible.VIN
                LEFT JOIN Vehicle_Van ON v.VIN = Vehicle_Van.VIN) t ON r.VIN = t.VIN
            WHERE ManufacturerName = '$make' 
            AND StartDate IS NOT NULL
            GROUP BY t.VehicleType
            ORDER BY RepairCount DESC, t.VehicleType";
}

function get_repair_by_make_type_sql($make, $type) {
    return "SELECT ModelName,
            COUNT(StartDate) AS RepairCount,
            ROUND(SUM(PartsCost), 2) AS TotalPartsCost,
            ROUND(SUM(LaborCharges), 2) AS TotalLaborCharges,
            ROUND(SUM(RepairCost), 2) AS TotalRepairCost
            FROM (SELECT v.VIN, ModelName, ManufacturerName, StartDate,
                COALESCE(LaborCharges, 0) AS LaborCharges,
                PartsCost,
                COALESCE(LaborCharges+PartsCost, 0) AS RepairCost
                FROM Vehicle v
                LEFT JOIN 
                (SELECT r.VIN, r.StartDate, r.LaborCharges,
                SUM(COALESCE(UnitPrice*QuantityUsed, 0)) AS PartsCost 
                FROM Repair r
                LEFT JOIN Part p ON r.VIN = p.VIN AND r.StartDate = p.StartDate
                GROUP BY r.VIN, r.StartDate) rp
                ON v.VIN = rp.VIN) r
            JOIN (SELECT v.VIN,
                (CASE
                    WHEN DoorCount IS NOT NULL THEN 'Car'
                    WHEN CargoCapacity IS NOT NULL THEN 'Truck'
                    WHEN RoofType IS NOT NULL THEN 'Convertible'
                    WHEN DriveTrainType IS NOT NULL THEN 'SUV'
                    WHEN DriverSideDoor IS NOT NULL THEN 'Van'
                    END) AS VehicleType
                FROM Vehicle v
                LEFT JOIN Vehicle_Car ON v.VIN = Vehicle_Car.VIN
                LEFT JOIN Vehicle_Truck ON v.VIN = Vehicle_Truck .VIN
                LEFT JOIN Vehicle_SUV ON v.VIN = Vehicle_SUV.VIN
                LEFT JOIN Vehicle_Convertible ON v.VIN = Vehicle_Convertible.VIN
                LEFT JOIN Vehicle_Van ON v.VIN = Vehicle_Van.VIN) t ON r.VIN = t.VIN
            WHERE ManufacturerName = '$make' AND VehicleType = '$type'
            AND StartDate IS NOT NULL
            GROUP BY ModelName
            ORDER BY RepairCount DESC, VehicleType";
}

$below_cost_sql = 
"SELECT DateSold, InvoicePrice, SoldPrice, ROUND(SoldPrice/InvoicePrice * 100, 2) AS ProfitRatio, COALESCE(CONCAT(I.FirstName, ' ',I.LastName), BusinessName) AS CustomerName, CONCAT(U.FirstName, ' ',U.LastName) AS SalespersonName
FROM Vehicle
LEFT JOIN Individual AS I ON Vehicle.CustomerID = I.CustomerID
LEFT JOIN Business AS B ON Vehicle.CustomerID = B.CustomerID
LEFT JOIN User AS U ON Vehicle.SalespeopleUsername = U.Username 
WHERE DateSold IS NOT NULL AND SoldPrice < InvoicePrice
ORDER BY DateSold DESC, ProfitRatio DESC";

$average_inventory_time_sql =
"SELECT 'Car' as VehicleType, ROUND(AVG(DATEDIFF(DateSold+1, DateAdded)), 2) as Days
FROM Vehicle_Car
JOIN Vehicle ON Vehicle_Car.VIN = Vehicle.VIN
WHERE Vehicle.CustomerID IS NOT NULL
UNION
SELECT 'Convertible' as VehicleType, ROUND(AVG(DATEDIFF(DateSold+1, DateAdded)), 2)
FROM Vehicle_Convertible
JOIN Vehicle ON Vehicle_Convertible.VIN = Vehicle.VIN
WHERE Vehicle.CustomerID IS NOT NULL
UNION
SELECT 'SUV' as VehicleType, ROUND(AVG(DATEDIFF(DateSold+1, DateAdded)), 2)
FROM Vehicle_SUV
JOIN Vehicle ON Vehicle_SUV.VIN = Vehicle.VIN
WHERE Vehicle.CustomerID IS NOT NULL
UNION
SELECT 'Truck' as VehicleType, ROUND(AVG(DATEDIFF(DateSold+1, DateAdded)), 2)
FROM Vehicle_Truck
JOIN Vehicle ON Vehicle_Truck.VIN = Vehicle.VIN
WHERE Vehicle.CustomerID IS NOT NULL
UNION
SELECT 'Van' as VehicleType, ROUND(AVG(DATEDIFF(DateSold+1, DateAdded)), 2)
FROM Vehicle_Van
JOIN Vehicle ON Vehicle_Van.VIN = Vehicle.VIN
WHERE Vehicle.CustomerID IS NOT NULL";

$parts_stats_sql = 
"SELECT VendorName, SUM(QuantityUsed) AS NumberOfParts, ROUND(SUM(QuantityUsed * UnitPrice), 2) AS TotalSpent
FROM Part
GROUP BY VendorName
ORDER BY TotalSpent DESC";

$sales_by_year_sql =
"SELECT YEAR(DateSold) as Year, COUNT(VIN) AS TotalVehiclesSold, ROUND(SUM(SoldPrice), 2) AS TotalSalesIncome, ROUND(SUM(SoldPrice) - SUM(InvoicePrice), 2) AS TotalNetIncome, ROUND(SUM(SoldPrice) / SUM(InvoicePrice) * 100, 2) AS ProfitRatio 
FROM Vehicle 
WHERE CustomerID IS NOT NULL 
GROUP BY YEAR(DateSold) 
ORDER BY YEAR(DateSold) DESC";

$sales_by_year_month_sql =
"SELECT YEAR(DateSold) as Year, MONTH(DateSold) AS Month, COUNT(VIN) AS TotalVehiclesSold, ROUND(SUM(SoldPrice), 2) AS TotalSalesIncome, ROUND(SUM(SoldPrice) - SUM(InvoicePrice), 2) AS TotalNetIncome, ROUND(SUM(SoldPrice) / SUM(InvoicePrice) * 100, 2) AS ProfitRatio 
FROM Vehicle 
WHERE CustomerID IS NOT NULL 
GROUP BY YEAR(DateSold), MONTH(DateSold)
ORDER BY YEAR(DateSold) DESC, MONTH(DateSold) DESC";

function get_sales_drilldown_by_year($year) {
    return "SELECT FirstName, LastName, COUNT(VIN) AS TotalVehiclesSold, ROUND(SUM(SoldPrice), 2) AS TotalSales
            FROM Vehicle JOIN User U ON Vehicle.SalespeopleUsername = Username WHERE YEAR(DateSold) = '$year'
            GROUP BY Username
            ORDER BY TotalVehiclesSold DESC, TotalSales DESC LIMIT 1";
}

function get_sales_drilldown_by_month($year, $month) {
    return "SELECT FirstName, LastName, COUNT(VIN) AS TotalVehiclesSold, ROUND(SUM(SoldPrice), 2) AS TotalSales
            FROM Vehicle JOIN User U ON Vehicle.SalespeopleUsername = Username 
            WHERE MONTH(DateSold) = '$month' AND YEAR(DateSold) = '$year'
            GROUP BY Username
            ORDER BY TotalVehiclesSold DESC, TotalSales DESC LIMIT 1";
}

$search_vehicle_get_manufacturer =
"SELECT DISTINCT ModelYear FROM Vehicle ORDER BY ModelYear DESC LIMIT 1;";

function search_by_vin($InputVIN) {
    $query = "SELECT vehicle.VIN,
		      (CASE
  			   WHEN DoorCount IS NOT NULL THEN 'Car'
  			   WHEN CargoCapacity IS NOT NULL THEN 'Truck'
  			   WHEN RoofType IS NOT NULL THEN 'SUV'
  			   WHEN DriveTrainType IS NOT NULL THEN 'Convertible'
  			   WHEN DriverSideDoor IS NOT NULL THEN 'Van'
  			   END) AS VehicleType, 
  			   Vehicle.ModelYear, Vehicle.ManufacturerName, Vehicle.ModelName, GROUP_CONCAT(ColorName) AS Colors, ROUND(Vehicle.InvoicePrice*1.25, 2) AS ListPrice, Vehicle.Description
  			   FROM Vehicle
			   JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
			   LEFT JOIN Vehicle_Car ON Vehicle.VIN = Vehicle_Car.VIN
			   LEFT JOIN Vehicle_Truck ON Vehicle.VIN = Vehicle_Truck .VIN
			   LEFT JOIN Vehicle_SUV ON Vehicle.VIN = Vehicle_SUV.VIN
			   LEFT JOIN Vehicle_Convertible ON Vehicle.VIN = Vehicle_Convertible.VIN
			   LEFT JOIN Vehicle_Van ON Vehicle.VIN = Vehicle_Van.VIN
			   WHERE Vehicle.VIN = '$InputVIN'";
    return $query;
}

function get_search_vehicle_query($VehicleType, $ManufacturerName, $ModelYear, $ColorName, $ListPrice, $Keyword, $Availability) {
$lquery = "SELECT vehicle.VIN,
		      (CASE
  			   WHEN DoorCount IS NOT NULL THEN 'Car'
  			   WHEN CargoCapacity IS NOT NULL THEN 'Truck'
  			   WHEN RoofType IS NOT NULL THEN 'Convertible'
  			   WHEN DriveTrainType IS NOT NULL THEN 'SUV'
  			   WHEN DriverSideDoor IS NOT NULL THEN 'Van'
  			   END) AS VehicleType, 
  			   Vehicle.ModelYear, Vehicle.ManufacturerName, Vehicle.ModelName, GROUP_CONCAT(ColorName) AS Colors, ROUND(Vehicle.InvoicePrice*1.25, 2) AS ListPrice, Vehicle.Description
  			   FROM Vehicle
			   JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
			   LEFT JOIN Vehicle_Car ON Vehicle.VIN = Vehicle_Car.VIN
			   LEFT JOIN Vehicle_Truck ON Vehicle.VIN = Vehicle_Truck .VIN
			   LEFT JOIN Vehicle_SUV ON Vehicle.VIN = Vehicle_SUV.VIN
			   LEFT JOIN Vehicle_Convertible ON Vehicle.VIN = Vehicle_Convertible.VIN
			   LEFT JOIN Vehicle_Van ON Vehicle.VIN = Vehicle_Van.VIN
			   WHERE 1 
               ";

    if (!empty($VehicleType)) {
        if ($VehicleType == "Car")
           {$lquery = $lquery." AND DoorCount IS NOT NULL";}
        if ($VehicleType == "Truck")
           {$lquery = $lquery." AND CargoCapacity IS NOT NULL";}
        if ($VehicleType == "Convertible")
           {$lquery = $lquery." AND RoofType IS NOT NULL";}
        if ($VehicleType == "SUV")
           {$lquery = $lquery." AND DriveTrainType IS NOT NULL";}
        if ($VehicleType == "Van")
           {$lquery = $lquery." AND DriverSideDoor IS NOT NULL";}

    }


    if (!empty($ManufacturerName)) {
        $lquery = $lquery." AND ManufacturerName = '$ManufacturerName'";
    }

    if (!empty($ModelYear)) {
        $lquery = $lquery." AND ModelYear = '$ModelYear'";
    }

    if (!empty($ListPrice)) {
        $lquery = $lquery." AND InvoicePrice*1.25 <= '$ListPrice'";
    }

    if (!empty($Keyword)) {
        $lquery = $lquery." AND (Description LIKE BINARY '%$Keyword%' OR ManufacturerName LIKE BINARY '%$Keyword%' OR ModelYear LIKE '%$Keyword%' OR ModelName LIKE BINARY '%$Keyword%')";
    }

    //manager filter
    if (!empty($Availability) ) {
        if ($Availability === "Sold" ) {
            $lquery = $lquery." AND DateSold IS NOT NULL";}
        if ($Availability === "Unsold" ) {
            $lquery = $lquery." AND DateSold IS NULL";}
    }
    else $lquery = $lquery." AND DateSold IS NULL";

    $lquery = $lquery." GROUP BY Vehicle.VIN";

    // display all colors
    if (!empty($ColorName)) {
        $lquery = $lquery." HAVING Colors LIKE '%$ColorName%'";
    }

    $lquery = $lquery." ORDER BY Vehicle.VIN ASC;";

    return $lquery;
}

//function for vehicle profile
function get_vehicle_profile_sql($VIN) {
    return "SELECT Vehicle.VIN, Vehicle.ModelYear, Vehicle.ModelName, Vehicle.ManufacturerName, (CASE
  WHEN DoorCount IS NOT NULL THEN 'Car'
  WHEN CargoCapacity IS NOT NULL THEN 'Truck'
  WHEN RoofType IS NOT NULL THEN 'Convertible'
  WHEN DriveTrainType IS NOT NULL THEN 'SUV'
  WHEN DriverSideDoor IS NOT NULL THEN 'Van'
  END) AS VehicleType, DateSold,
GROUP_CONCAT(ColorName) AS Colors, Vehicle.InvoicePrice, ROUND(Vehicle.InvoicePrice*1.25, 2) AS ListPrice, Vehicle_Car.DoorCount, Vehicle_Truck. CargoCapacity, Vehicle_Truck. CargoCoverType, Vehicle_Truck. RearAxisCount, Vehicle_SUV.DriveTrainType, Vehicle_SUV.CupHolderCount, Vehicle_Convertible.RoofType, Vehicle_Convertible.BackSeatCount, Vehicle_Van. DriverSideDoor, Vehicle.Description
FROM Vehicle
JOIN Vehicle_Color ON Vehicle.VIN = Vehicle_Color.VIN
LEFT JOIN Vehicle_Car ON Vehicle.VIN = Vehicle_Car.VIN
LEFT JOIN Vehicle_Truck ON Vehicle.VIN = Vehicle_Truck .VIN
LEFT JOIN Vehicle_SUV ON Vehicle.VIN = Vehicle_SUV.VIN
LEFT JOIN Vehicle_Convertible ON Vehicle.VIN = Vehicle_Convertible.VIN
LEFT JOIN Vehicle_Van ON Vehicle.VIN = Vehicle_Van.VIN
WHERE Vehicle.VIN = '$VIN'";
}

//clerk query
function get_clerk_sql($VIN) {
    return "SELECT CONCAT(User.FirstName, ' ', User.LastName) AS ClerkName, InvoicePrice, DateAdded 
FROM Vehicle
LEFT JOIN User ON Vehicle.ClerkUsername = User.Username
WHERE Vehicle.VIN = '$VIN'";
}

//sales query
function get_sales_query($VIN) {
    return "SELECT CONCAT(User.FirstName, ' ', User.LastName) AS SalespersonName,
Email, StreetAddress, City, State, ZipCode, Phone, COALESCE(CONCAT(I.FirstName, ' ',I.LastName), BusinessName) AS CustomerName, PrimaryContactName, PrimaryContactTitle,
DateSold, SoldPrice, InvoicePrice, DateAdded
FROM Vehicle
JOIN User ON Vehicle.SalespeopleUsername = User.Username
JOIN Customer ON Vehicle.CustomerID = Customer.CustomerID
LEFT JOIN Individual AS I ON Vehicle.CustomerID = I.CustomerID
LEFT JOIN Business AS B ON Vehicle.CustomerID = B.CustomerID
WHERE Vehicle.VIN = '$VIN' AND DateSold IS NOT NULL
";
}

//Repair query
function get_repair_query($VIN) {
    return "SELECT Repair.StartDate, EndDate, LaborCharges, ROUND(COALESCE(SUM(UnitPrice*QuantityUsed), 0), 2) AS PartsCost, ROUND((COALESCE(SUM(UnitPrice*QuantityUsed), 0) + LaborCharges), 2) AS TotalCost,
CONCAT(User.FirstName, ' ', User.LastName) AS ServiceWriterName, COALESCE(CONCAT(Individual.FirstName, ' ',Individual.LastName), BusinessName) AS CustomerName
FROM Repair 
INNER JOIN User ON Repair.ServiceWriterUsername = User.Username
INNER JOIN Vehicle ON Repair.VIN = Vehicle.VIN
LEFT JOIN Part ON Repair.VIN = Part.VIN AND Repair.StartDate = Part.StartDate
INNER JOIN Customer ON Repair.CustomerID = Customer.CustomerID
LEFT JOIN Individual ON Repair.CustomerID = Individual.CustomerID
LEFT JOIN Business ON Repair.CustomerID = Business.CustomerID
WHERE Repair.VIN = '$VIN'
GROUP BY Repair.VIN, Repair.StartDate";
}

// Search
$get_all_manufacturers_sql = "SELECT `ManufacturerName` FROM `Manufacturer`;";
$get_all_colors_sql = "SELECT `ColorName` FROM `Color`;";
?>
