<?php
//Connection for MySQL database.
$conn = new mysqli(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASS,MYSQL_DATABASE) or die("Database Connection Failed");
$conn->set_charset("utf8mb4");

$connList = new mysqli(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASS,MYSQL_DATABASE_LIST) or die("Database Connection Failed");
$connList->set_charset("utf8mb4");

// Include DB functions
require_once(ROOT.INC_HELPERS."inc.mysql.php");