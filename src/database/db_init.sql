
create table customer (
	customer_id integer primary key auto_increment,
	name text,
	phone text,
	zipcode text
);

create table vehicle (
	vehicle_id integer primary key auto_increment,
	owner integer references customer(customer_id),
	year text,
	make text,
	model text,
	trim text
);

create table employee (
	employee_id integer primary key auto_increment,
	name text,
	phone text,
	position enum('manager', 'mechanic')
);

create table garage (
	garage_id integer primary key auto_increment,
	name text,
	zipcode text
);

create table service (
	service_id integer primary key auto_increment,
	customer_id integer references customer(customer_id),
	vehicle_id integer references vehicle(vehicle_id),
	mechanic integer references employee(employe_id),
	garage_id integer references garage(garage_id),
	est_completion datetime,
	description text
);

create table task (
	task_id integer primary key auto_increment,
	service_id integer references service(service_id),
	description text
);

insert into customer (customer_id, name, phone, zipcode) values
(1, 'Greg Lewis', 1234567890, 19104),
(2, 'Sophie Linn', 1245987349, 45873),
(3, 'Juan Alvarez', 4523487190, 32132);

insert into vehicle (vehicle_id, owner, year, make, model, trim) values
(1, 1, 2016, 'Toyota', 'Camry', null),
(2, 2, 2010, 'Chevy', 'Cruze', null),
(3, 3, 2014, 'Honda', 'Civic', null);

insert into employee (employee_id, name, phone, position) values
(1, 'Tod',  4574892381, 'mechanic'),
(2, 'Greg', 5487359012, 'mechanic'),
(3, 'Bob',  2349129384, 'mechanic');

insert into garage (garage_id, name, zipcode) values
(1, 'Garage 1', 32132),
(2, 'Garage 2', 19104),
(3, 'Garage 3', 45873);

insert into service (customer_id, vehicle_id, est_completion, mechanic, garage_id, description) values
(1, 1, '2016-11-2', 1, 2, 'Oil change'),
(2, 2, '2016-11-10', 2, 3, 'Brake repair'),
(3, 3, '2016-11-12', 3, 1, 'Inspection');

