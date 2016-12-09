-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.15-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5137
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table gears.appointment: ~9 rows (approximately)
/*!40000 ALTER TABLE `appointment` DISABLE KEYS */;
INSERT INTO `appointment` (`appointment_id`, `subject`, `update_time`, `create_time`, `description`, `event_time`, `start_time`, `end_time`, `customer_id`, `state`) VALUES
	(1, 'fix my car', '2016-12-06 14:11:27', '2016-12-03 00:11:26', 'fix my car', '2016-12-07 13:30:00', '2016-12-03 00:11:58', '2016-12-06 14:11:27', 6, 3),
	(2, 'regular maintenance', '2016-12-06 14:06:38', '2016-12-03 00:11:26', 'fix my car', '2016-12-07 11:11:54', '2016-12-06 10:11:58', '2016-12-06 14:06:38', 6, 3),
	(3, 'regular maintenance 1', '2016-12-08 09:59:45', '2016-12-06 00:11:26', 'regular maintenance 1', '2016-12-07 20:11:54', '1970-01-01 00:00:00', '2016-12-06 22:53:42', 6, 3),
	(4, 'Take the struts out and replace', '2016-12-08 11:46:15', '2016-12-07 11:50:43', 'Take the struts out and replace', '2016-12-07 11:50:00', '2016-12-08 09:26:50', '1970-01-01 00:00:00', 3, 1),
	(5, 'happy holiday!', '2016-12-07 11:53:28', '2016-12-07 11:53:28', 'Take the struts out and replace', '2016-12-07 13:52:00', '1970-01-01 00:00:00', '1970-01-01 00:00:00', 1, 1),
	(6, 'change gear fluid', '2016-12-08 13:23:07', '2016-12-08 13:14:05', '1 do a flush\r\n2 change fluid\r\n3 clear error codes if any', '2016-12-08 15:12:00', '2016-12-08 13:23:07', '1970-01-01 00:00:00', 1, 2),
	(7, 'project presetation', '2016-12-08 14:09:00', '2016-12-08 14:09:00', 'let\'s do our best!\r\nthe presentation should follow:\r\n   - what we designed\r\n   - what the problems are', '2016-12-09 10:07:00', '1970-01-01 00:00:00', '1970-01-01 00:00:00', 1, 1),
	(8, 'Broken Car', '2016-12-08 15:13:52', '2016-12-08 15:08:22', 'Help Gears, my car broke down and I cant make it to my database presentation', '2016-12-09 15:07:00', '2016-12-08 15:09:56', '2016-12-08 15:12:12', 11, 3),
	(9, 'Popped Tire', '2016-12-08 16:25:34', '2016-12-08 16:19:35', 'Help Gears, I am going to miss my database presentation', '2016-12-08 16:45:00', '2016-12-08 16:21:31', '2016-12-08 16:24:25', 12, 3);
/*!40000 ALTER TABLE `appointment` ENABLE KEYS */;

-- Dumping data for table gears.conventionvehicle: ~21 rows (approximately)
/*!40000 ALTER TABLE `conventionvehicle` DISABLE KEYS */;
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES
	(1, 1, 0, 'Toyota', 1997, 'LE', 'Camry'),
	(2, 1, 1, 'Toyota', 2005, 'XE', 'RAV4'),
	(3, 1, 1, 'Toyota', 2005, 'LE', 'RAV4'),
	(4, 1, 0, 'Toyota', 1997, 'XLE', 'Camry'),
	(5, 0, 0, 'Ford', 2007, 'S', 'Focus'),
	(6, 1, 0, 'Ford', 2007, 'SE', 'Focus'),
	(7, 1, 0, 'Ford', 2007, 'SES', 'Focus'),
	(8, 1, 1, 'Ford', 2007, 'ST', 'Focus'),
	(9, 1, 1, 'Lincoln', 2011, 'Base', 'MKX SUV'),
	(10, 1, 1, 'Lincoln', 2002, 'N/A', 'Continental'),
	(11, 1, 0, 'Honda', 2013, 'EX', 'Civic'),
	(12, 1, 0, 'Honda', 2012, 'LX', 'Civic'),
	(13, 0, 0, 'Honda', 2014, 'Si', 'Civic'),
	(14, 0, 0, 'Honda', 2012, 'Si', 'Civic'),
	(15, 1, 1, 'Honda', 2012, 'EX', 'Accord'),
	(16, 1, 1, 'Honda', 2015, 'Hybrid', 'Accord'),
	(17, 1, 1, 'Honda', 2015, 'Coupe', 'Accord'),
	(18, 1, 1, 'Honda', 2013, 'LX', 'Accord'),
	(19, 1, 1, 'BMW', 2011, 'xDrive', '328i'),
	(20, 1, 1, 'BMW', 2013, 'xDrive', '328i'),
	(21, 1, 1, 'BMW', 2017, 'xDrive SULEV', '330i');
/*!40000 ALTER TABLE `conventionvehicle` ENABLE KEYS */;

-- Dumping data for table gears.customer: ~12 rows (approximately)
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES
	(1, 'Jared W.', 'Williams', '215-666-7777', '18928-7899'),
	(2, 'Bill', 'Fisher', '613-567-9999', '19140'),
	(3, 'Harry', 'Hollander', '215-222-1111', '19001'),
	(4, 'Sarah', 'Tarmano', '234-123-7777', '19001'),
	(5, 'Simon', 'Jackson', '210-332-5543', '19310'),
	(6, 'Patrick', 'Hope', '610-125-8973', '31001-3462'),
	(7, 'Bill', 'Allany', '610-432-0372', '19003'),
	(8, 'Tim', 'Fallon', '215-239-0987', '23000-9087'),
	(9, 'Susanne', 'Greenwish', '622-342-6710', '21011-0421'),
	(10, 'Database', 'Man', '123-456-7890', '19131'),
	(11, 'This', 'guy', '123-456-7945', '19131'),
	(12, 'Tom', 'Cruise', '123-456-7893', '19131');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping data for table gears.customervehicle: ~12 rows (approximately)
/*!40000 ALTER TABLE `customervehicle` DISABLE KEYS */;
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `convention_vehicle_id`, `mileage`, `vin`) VALUES
	(1, 2, 5, 75000, '1FADP3L95DL191708'),
	(2, 3, 7, 100000, '1FADP3F21EL134616'),
	(3, 1, 7, 126000, '1FAHP3GN4AW190908'),
	(4, 5, 1, 200000, '4T1BE46K97U118881'),
	(5, 4, 2, 57000, '4T4BF1FK3CR271199'),
	(6, 8, 4, 50000, 'JTDBF32K520051827'),
	(7, 7, 3, 70000, '4T1BF1FK8DU287877'),
	(8, 6, 6, 350000, '1FADP3L9XEL212179'),
	(9, 9, 21, 5000, 'WBSWD9C51AP362771'),
	(10, 10, 21, 100, '1osnfaiuwnfmaows'),
	(11, 11, 3, 45000, '1idnarjewkw12'),
	(12, 12, 2, 20000, '1ksdkasofj32');
/*!40000 ALTER TABLE `customervehicle` ENABLE KEYS */;

-- Dumping data for table gears.employee: ~4 rows (approximately)
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` (`emp_id`, `phone_number`, `first_name`, `last_name`, `emp_code`, `is_manager`, `state`) VALUES
	(1, '610-333-9999', 'Caprice', 'Mosser', 'CM0001', 1, 7),
	(3, '610-890-0089', 'Ruthann', 'Shirk', 'RS0003', 0, 8),
	(4, '215-265-2447', 'Albert', 'Summy', 'AS0004', 0, 7),
	(5, '215-987-0102', 'Randall', 'Rayburn', 'RR0005', 0, 7);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;

-- Dumping data for table gears.inventoryitem: ~32 rows (approximately)
/*!40000 ALTER TABLE `inventoryitem` DISABLE KEYS */;
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `category`, `unit`, `unit_price`) VALUES
	(1, 1, 'VCV10JPP', 'front-kit', 'PARTS', 'pair', 70),
	(2, 1, 'MCV10', 'front-kit', 'PARTS', 'pair', 70),
	(3, 2, 'SXV20JPPCE W/14” DW', 'front-kit', 'PARTS', 'pair', 80),
	(4, 3, 'SXV20JPPCE W/14” DW', 'front-kit', 'PARTS', 'pair', 80),
	(5, 3, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80),
	(6, 2, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15),
	(7, 3, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15),
	(8, 4, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15),
	(11, 4, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80),
	(13, 4, 'MCV10', 'front-kit', 'PARTS', 'pair', 80),
	(14, 5, 'MCV10', 'front-kit', 'PARTS', 'pair', 80),
	(15, 5, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80),
	(16, 5, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15),
	(17, 6, 'INSPECT-10', 'inspection', 'LABOR', 'hour', 54),
	(18, 7, 'INSPECT-10', 'inspection', 'LABOR', 'hour', 54),
	(19, 1, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(20, 6, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15),
	(21, 6, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80),
	(22, 6, 'MCV10', 'front-kit', 'PARTS', 'pair', 70),
	(23, 9, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(24, 10, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(25, 11, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(26, 12, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(27, 13, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(28, 14, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(29, 15, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(30, 16, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(31, 17, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(32, 18, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(33, 19, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(34, 20, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54),
	(35, 21, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54);
/*!40000 ALTER TABLE `inventoryitem` ENABLE KEYS */;

-- Dumping data for table gears.invoice: ~4 rows (approximately)
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
INSERT INTO `invoice` (`invoice_id`, `appointment_id`, `create_time`, `update_time`, `state`, `tax_rate`, `amount_due`, `amount_payed`, `discount_rate`) VALUES
	(1, 3, '2016-12-06 23:54:25', '2016-12-08 09:59:45', 10, 0.06, 35.62, 35.62, 0.8),
	(2, 1, '2016-12-06 23:54:25', '2016-12-08 09:59:45', 10, 0.06, 942, 753.6, 0.2),
	(3, 8, '2016-12-08 15:13:16', '2016-12-08 15:13:52', 10, 0.06, 84.8, 84.8, 0.5),
	(4, 9, '2016-12-08 16:25:16', '2016-12-08 16:25:34', 10, 0.06, 100.7, 100.7, 0.5);
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;

-- Dumping data for table gears.job: ~6 rows (approximately)
/*!40000 ALTER TABLE `job` DISABLE KEYS */;
INSERT INTO `job` (`job_id`, `job_key`, `create_time`, `summary`, `description`, `state`, `appointment_id`, `mechanic_id`, `customer_vehicle_id`) VALUES
	(1, 'gears-patr-20161204165740-0500', '2016-12-04 16:57:40', 'oil change', 'oil change 4 bottles', 3, 1, 5, 8),
	(2, 'gears-patr-20161206110840-0500', '2016-12-06 11:08:40', 'regular maintenance', 'inspection and oil change', 3, 2, 1, 8),
	(6, 'gears-patr-20161206225124-0500', '2016-12-06 22:51:24', 'regular maintenance 1', 'regular maintenance 1, we are about to begin!\r\nchange the oil using Mobil 1 5w-40 SN', 3, 3, 4, 8),
	(8, 'gears-jare-20161208132051-0500', '2016-12-08 13:20:51', 'change gear fluid and check error code', '1 do a flush\r\n2 change fluid\r\n3 clear error codes if any\r\n-----------------------------------\r\n8 bottles of 75w-90 trans fluid are needed!', 1, 6, 3, 3),
	(9, 'gears-this-20161208150917-0500', '2016-12-08 15:09:17', 'Broken Car', 'Help Gears, my car broke down and I cant make it to my database presentation', 3, 8, 4, 11),
	(10, 'gears-tomc-20161208162021-0500', '2016-12-08 16:20:21', 'Popped Tire', 'Help Gears, I am going to miss my database presentation', 3, 9, 4, 12);
/*!40000 ALTER TABLE `job` ENABLE KEYS */;

-- Dumping data for table gears.state: ~11 rows (approximately)
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
INSERT INTO `state` (`state_code`, `state_name`) VALUES
	(7, 'AVAILABLE'),
	(8, 'BUSY'),
	(4, 'CANCELLED'),
	(3, 'DONE'),
	(2, 'INSERVICE'),
	(5, 'INSPECTING'),
	(11, 'INVOICING'),
	(1, 'NEW'),
	(6, 'ONGOING'),
	(10, 'PAYED'),
	(9, 'PENDING');
/*!40000 ALTER TABLE `state` ENABLE KEYS */;

-- Dumping data for table gears.task: ~12 rows (approximately)
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` (`inventory_item_id`, `worksheet_job_id`, `quantity`, `is_done`, `finish_time`, `amount_cost`) VALUES
	(3, 10, 2, 1, '2016-12-08 16:24:25', 160),
	(4, 9, 2, 1, '2016-12-08 15:12:12', 160),
	(6, 10, 2, 1, '2016-12-08 16:24:25', 30),
	(17, 1, 3, 1, '2016-12-06 14:11:27', 162),
	(17, 2, 5, 1, '2016-12-06 14:06:38', 270),
	(17, 6, 2, 1, '2016-12-06 22:53:42', 108),
	(18, 8, 1, 0, '1970-01-01 00:00:00', 54),
	(20, 1, 4, 1, '2016-12-06 14:11:27', 60),
	(20, 6, 4, 1, '2016-12-06 22:53:42', 60),
	(21, 1, 2, 1, '2016-12-06 14:11:27', 160),
	(21, 2, 4, 1, '2016-12-06 14:06:38', 320),
	(22, 1, 8, 1, '2016-12-06 14:11:27', 560);
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Dumping data for table gears.worksheet: ~6 rows (approximately)
/*!40000 ALTER TABLE `worksheet` DISABLE KEYS */;
INSERT INTO `worksheet` (`job_id`, `vehicle_mileage`, `end_time`, `start_time`) VALUES
	(1, 300000, '2016-12-06 14:11:27', '2016-12-05 09:00:00'),
	(2, 350000, '2016-12-06 14:06:38', '2016-12-06 12:54:29'),
	(6, 350000, '2016-12-06 22:53:42', '2016-12-06 22:52:38'),
	(8, 200000, '1970-01-01 00:00:00', '2016-12-08 13:23:36'),
	(9, 45000, '2016-12-08 15:12:12', '2016-12-08 15:11:26'),
	(10, 20000, '2016-12-08 16:24:25', '2016-12-08 16:22:01');
/*!40000 ALTER TABLE `worksheet` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
