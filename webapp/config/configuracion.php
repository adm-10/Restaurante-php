<?php
/* Database credentials.  */
define('DB_SERVER', 'db');
define('DB_USERNAME', 'quevedo');
define('DB_PASSWORD', 'quevedo');
define('DB_NAME', 'quevedodb');

/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}