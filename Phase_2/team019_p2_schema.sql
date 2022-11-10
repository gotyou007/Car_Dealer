-- CREATE USER
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY ‘password’;
DROP DATABASE IF EXISTS `cs6400_fa21_team019`;
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa21_team019
	DEFAULT CHARACTER SET utf8mb4
	DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa21_team019;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO ‘team019’@’localhost’;
GRANT ALL PRIVILEGES ON `team019`.* TO ‘team019’@’localhost’;
GRANT ALL PRIVILEGES ON `cs6400_fa21_team019`.* TO ‘team019’@’localhost’;
FLUSH PRIVILEGES;

-- Tables

-- Vehicle

CREATE TABLE Vehicle(
  VIN varchar(17) NOT NULL,
  ManufacturerName varchar(50) NOT NULL,
  ModelName varchar(50) NOT NULL,
  ModelYear int NOT NULL,
  InvoicePrice float NOT NULL,
  ClerkUsername varchar(60) NOT NULL,
  DateAdded date NOT NULL,
  SoldPrice float NULL,
  DateSold date NULL,
  SalespeopleUsername varchar(60) NULL,
  CustomerID int NULL,
  Description varchar(100) NULL,
  PRIMARY KEY (VIN)
);

-- Manufacturer
CREATE TABLE Manufacturer(
  ManufacturerName varchar(50) NOT NULL,
  PRIMARY KEY (ManufacturerName)
);

-- Color
CREATE TABLE Color(
  ColorName varchar(10) NOT NULL,
  PRIMARY KEY (ColorName)
);

CREATE TABLE Vehicle_Color(
  VIN varchar(17) NOT NULL,
  ColorName varchar(10) NOT NULL,
  PRIMARY KEY (VIN, ColorName)
);

CREATE TABLE Vehicle_Car(
  VIN varchar(17) NOT NULL,
  DoorCount int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Vehicle_Truck(
  VIN varchar(17) NOT NULL,
  CargoCapacity int NOT NULL,
  CargoCoverType varchar(60) NULL,
  RearAxisCount int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Vehicle_SUV(
  VIN varchar(17) NOT NULL,
  RoofType varchar(60) NOT NULL,
  BackSeatCount int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Vehicle_Convertible(
  VIN varchar(17) NOT NULL,
  DriveTrainType varchar(60) NOT NULL,
  CupHolderCount int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Vehicle_Van(
  VIN varchar(17) NOT NULL,
  DriverSideDoor boolean NOT NULL,
  PRIMARY KEY (VIN)
);

-- User

CREATE TABLE User (
  Username varchar(60) NOT NULL,
  Password varchar(60) NOT NULL,
  FirstName varchar(60) NOT NULL,
  LastName varchar(60) NOT NULL,
  UserType varchar(20) NOT NULL,
  PRIMARY KEY (Username)
);


-- Repair

CREATE TABLE Repair (
  VIN varchar(17) NOT NULL,
  StartDate date NOT NULL,
  CustomerID int NOT NULL,
  EndDate date NULL,
  Odometer int NULL,
  LaborCharges float NULL,
  Description varchar(1000) NOT NULL,
  ServiceWriterUsername varchar(200) NOT NULL,
  PRIMARY KEY (VIN, StartDate)
);

CREATE TABLE Part (
  VIN varchar(17) NOT NULL,
  StartDate date NOT NULL,
  PartNumber varchar(50) NOT NULL,
  QuantityUsed int NOT NULL,
  UnitPrice float NOT NULL,
  VendorName varchar(60) NOT NULL,
  PRIMARY KEY (VIN, StartDate, PartNumber)
);


-- Customer

CREATE TABLE Customer (
  CustomerID int NOT NULL AUTO_INCREMENT,
  Email varchar(250) DEFAULT NULL,
  StreetAddress varchar(250) NOT NULL,
  City varchar(50) NOT NULL,
  State varchar(50) NOT NULL,
  ZipCode varchar(20) NOT NULL,
  Phone varchar(250) NOT NULL,
  PRIMARY KEY (CustomerID)
);

CREATE TABLE Individual (
  DriverLicense varchar(12) NOT NULL,
  CustomerID int NOT NULL,
  FirstName varchar(100) NOT NULL,
  LastName varchar(100) NOT NULL,
  PRIMARY KEY (DriverLicense)
);

CREATE TABLE Business (
  TaxID varchar(12) NOT NULL,
  CustomerID int NOT NULL,
  BusinessName varchar(100) NOT NULL,
  PrimaryContactName varchar(100) NOT NULL,
  PrimaryContactTitle varchar(50) NOT NULL,
  PRIMARY KEY (TaxID)
);



-- Alter Table

ALTER TABLE Vehicle
   ADD CONSTRAINT fk_Vehicle_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
   ADD CONSTRAINT fk_Vehicle_ClerkUsername_User_Username FOREIGN KEY (ClerkUsername) REFERENCES User(Username),
   ADD CONSTRAINT fk_Vehicle_ManufacturerName_Manufacturer_ManufacturerName FOREIGN KEY (ManufacturerName) REFERENCES Manufacturer(ManufacturerName),
   ADD CONSTRAINT fk_Vehicle_SalespeopleUsername_User_Username FOREIGN KEY (SalespeopleUsername) REFERENCES User(Username);

ALTER TABLE Vehicle_Color
  ADD CONSTRAINT fk_VehicleColor_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_VehicleColor_ColorName_Color_ColorName FOREIGN KEY (ColorName) REFERENCES Color (ColorName);

ALTER TABLE Vehicle_Car
  ADD CONSTRAINT fk_VehicleCar_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);

ALTER TABLE Vehicle_Truck
  ADD CONSTRAINT fk_VehicleTruck_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);

ALTER TABLE Vehicle_Convertible
  ADD CONSTRAINT fk_VehicleConvertible_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);

ALTER TABLE Vehicle_SUV
  ADD CONSTRAINT fk_VehicleSUV_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);

ALTER TABLE Vehicle_Van
  ADD CONSTRAINT fk_VehicleVan_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);

ALTER TABLE Repair
   ADD CONSTRAINT fk_Vehicle_VIN_Repair_VIN FOREIGN KEY 
(VIN) REFERENCES Vehicle(VIN),
   ADD CONSTRAINT fk_Repair_ServiceWriterUsername_User_Username FOREIGN KEY 
(ServiceWriterUsername) REFERENCES User(Username);

ALTER TABLE Part 
   ADD CONSTRAINT fk_Repair_PK_Part_FK FOREIGN KEY 
(VIN, StartDate) REFERENCES Repair(VIN, StartDate);

ALTER TABLE Individual
  ADD CONSTRAINT fk_Individual_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID);

ALTER TABLE Business 
  ADD CONSTRAINT fk_Business_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID);
