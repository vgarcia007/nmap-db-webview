<?php
$TABLE_OPTIONS = '
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci
ENGINE=INNODB';

$TABLES['APP'] = '
CREATE TABLE IF NOT EXISTS app (
id INT AUTO_INCREMENT PRIMARY KEY,
firstrun VARCHAR(30) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)' . $TABLE_OPTIONS;


$TABLES['USER'] = '
CREATE TABLE IF NOT EXISTS user (
id INT AUTO_INCREMENT PRIMARY KEY,
first_name VARCHAR(30) NOT NULL,
second_name VARCHAR(30) NOT NULL,
UserName VARCHAR(50) NOT NULL,
email VARCHAR(50) NOT NULL,
password VARCHAR(255) NOT NULL,
permissions VARCHAR(255) NOT NULL,
info TEXT NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)' . $TABLE_OPTIONS;

$TABLES['hosts'] = '
CREATE TABLE IF NOT EXISTS hosts (
id INT AUTO_INCREMENT PRIMARY KEY,
state varchar(7) NOT NULL,
ipv4 varchar(15) NOT NULL,
mac varchar(17) NOT NULL,
vendor varchar(255) NOT NULL,
hostname varchar(255) NOT NULL,
os varchar(255) NOT NULL,
uptime_seconds int NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)' . $TABLE_OPTIONS;



$TABLES['ports'] = '
CREATE TABLE IF NOT EXISTS ports (
id INT AUTO_INCREMENT PRIMARY KEY,
hosts_id int NOT NULL,
portid int NOT NULL,
protocol varchar(50) NOT NULL,
state varchar(7) NOT NULL,
service varchar(255) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)' . $TABLE_OPTIONS;


?>