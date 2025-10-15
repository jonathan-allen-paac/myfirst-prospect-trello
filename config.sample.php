<?php
// Configuration array. Copy file and name config.php

// Global settings
ini_set("display_errors","On");

// Global variables for sitewide use
// Domain
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
define("PROTOCOL", $protocol);
define("DOMAIN", $_SERVER['HTTP_HOST']);
// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$webhook_url = "";

// Start a session and timestamp for process timings
if(!isset($_SESSION)) {
    session_start();
}
$msc=microtime(true);

// Server settings for MySQL database
define('MYSQL_HOST',"127.0.0.1"); //fill this in, 127.0.0.1 for DB on same server as this script - avoid the use of hostnames and stick to IP's to reduce resolution time
define('MYSQL_DATABASE',"call_logging"); //fill this in
define('MYSQL_DATABASE_LIST',"call_logging"); //fill this in
define('MYSQL_USERNAME',""); //fill this in
define('MYSQL_PASS',""); //fill this in

// Server settings for MSSQL database
define('MSSQL_HOST',""); //fill this in
define('MSSQL_DATABASE',""); //fill this in
define('MSSQL_DATABASE_SYRINX',""); //fill this in
define('MSSQL_USERNAME',""); //fill this in
//define('MSSQL_PASS',""); //fill this in
define('MSSQL_PASS',""); //fill this in

// Server settings for SMTP
define('SMTP_AUTH',true); //fill this in
define('SMTP_TLS',true); //fill this in
define('SMTP_PORT',"587"); //fill this in
define('SMTP_HOST',""); //fill this in
define('SMTP_USERNAME',""); //fill this in
define('SMTP_PASS',""); //fill this in
define('EMAIL_FROM',""); //fill this in
define('EMAIL_NAME',""); //fill this in

// Directories
preg_match("/^(.*myfirst-prospect-trello)/i",__DIR__,$matches);
define('ROOT',$matches[1]."/");
define('INC',"src/");
define('INC_CONFIG',"src/config/");
define('INC_MODELS',"src/models/");
define('INC_HELPERS',"src/helpers/");
define('INC_PUBLIC',"public/");

$notificationEmails = array("errors@myfirst.com","jonathan.allen@paac-it.com");

// Global includes
require_once(ROOT.INC_HELPERS."inc.errors.php");
require_once(ROOT.INC_HELPERS."inc.security.php");
require_once(ROOT.INC_HELPERS."inc.functions.php");
require_once(ROOT.INC_CONFIG."inc.mssql.php");
require_once(ROOT.INC_CONFIG."inc.mysql.php");
