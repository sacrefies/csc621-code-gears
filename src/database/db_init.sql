USE gears;
-- states
INSERT INTO `state` (`state_code`, `state_name`) VALUES (7, 'AVAILABLE');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (8, 'BUSY');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (4, 'CANCELLED');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (3, 'DONE');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (2, 'INSERVICE');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (5, 'INSPECTING');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (11, 'INVOICING');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (1, 'NEW');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (6, 'ONGOING');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (10, 'PAYED');
INSERT INTO `state` (`state_code`, `state_name`) VALUES (9, 'PENDING');
-- conventional vehicles
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (1, 1, 0, 'Toyota', 1997, 'LE', 'Camry');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (2, 1, 1, 'Toyota', 2005, 'XE', 'RAV4');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (3, 1, 1, 'Toyota', 2005, 'LE', 'RAV4');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (4, 1, 0, 'Toyota', 1997, 'XLE', 'Camry');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (5, 0, 0, 'Ford', 2007, 'S', 'Focus');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (6, 1, 0, 'Ford', 2007, 'SE', 'Focus');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (7, 1, 0, 'Ford', 2007, 'SES', 'Focus');
INSERT INTO `conventionvehicle` (`vehicle_id`, `is_auto_trans`, `is_all_wheel`, `make`, `year`, `trim`, `model`) VALUES (8, 1, 1, 'Ford', 2007, 'ST', 'Focus');
-- inventory items
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (1, 1, 'VCV10JPP', 'front-kit', 'PARTS', 'pair', 70);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (2, 1, 'MCV10', 'front-kit', 'PARTS', 'pair', 70);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (3, 2, 'SXV20JPPCE W/14” DW', 'front-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (4, 3, 'SXV20JPPCE W/14” DW', 'front-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (5, 3, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (6, 2, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (7, 3, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (8, 4, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (11, 4, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (13, 4, 'MCV10', 'front-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (14, 5, 'MCV10', 'front-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (15, 5, 'SXV10', 'rear-kit', 'PARTS', 'pair', 80);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (16, 5, 'PK20R11', 'spark plugs', 'PARTS', 'plug', 15);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (17, 6, 'INSPECT-10', 'inspection', 'LABOR', 'hour', 54);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (18, 7, 'INSPECT-10', 'inspection', 'LABOR', 'hour', 54);
INSERT INTO `inventoryitem` (`item_id`, `convention_vehicle_id`, `item_code`, `part_name`, `cateory`, `unit`, `unit_price`) VALUES (19, 1, 'INSPECT-20', 'inspection', 'LABOR', 'hour', 54);
-- customer
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (1, 'Jared', 'Williams', '215-666-7777', NULL);
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (2, 'Bill', 'Fisher', '613-567-9999', '19140');
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (4, 'Sarah', 'Tarmano', '234-123-7777', '19001');
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (3, 'Harry', 'Hollander', '215-222-1111', '19001');
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (5, 'Simon', 'Jackson', '210-332-5543', '19310');
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (8, 'Tim', 'Fallon', '215-239-0987', NULL);
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (7, 'Bill', 'Allany', '610-432-0372', '19003');
INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `phone_number`, `customer_zip`) VALUES (6, 'Patrick', 'Hope', '610-125-8973', NULL);
-- customer vehicles
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (1, 2, '1FADP3L95DL191708', 5, 75000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (2, 3, '1FADP3F21EL134616', 7, 100000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (3, 1, '1FAHP3GN4AW190908', 7, 126000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (4, 5, '4T1BE46K97U118881', 1, 200000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (5, 4, '4T4BF1FK3CR271199', 2, 57000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (6, 8, 'JTDBF32K520051827', 4, 50000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (7, 7, '4T1BF1FK8DU287877', 3, 70000);
INSERT INTO `customervehicle` (`customer_vehicle_id`, `customer_id`, `vin`, `convention_vehicle_id`, `mileage`) VALUES (8, 6, '1FADP3L9XEL212179', 6, 230000);
-- employees
INSERT INTO `employee` (`emp_id`, `phone_number`, `first_name`, `last_name`, `emp_code`, `is_manager`, `state`) VALUES (1, '610-333-9999', 'Caprice', 'Mosser', 'CM0001', 1, 7);
INSERT INTO `employee` (`emp_id`, `phone_number`, `first_name`, `last_name`, `emp_code`, `is_manager`, `state`) VALUES (3, '610-890-0089', 'Ruthann', 'Shirk', 'RS0003', 0, 7);
INSERT INTO `employee` (`emp_id`, `phone_number`, `first_name`, `last_name`, `emp_code`, `is_manager`, `state`) VALUES (4, '215-265-2447', 'Albert', 'Summy', 'AS0004', 0, 7);
INSERT INTO `employee` (`emp_id`, `phone_number`, `first_name`, `last_name`, `emp_code`, `is_manager`, `state`) VALUES (5, '215-987-0102', 'Randall', 'Rayburn', 'RR0005', 0, 7);
