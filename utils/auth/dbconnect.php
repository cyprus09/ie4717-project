<?php
define('DBSERVER', 'localhost'); // Database server
define('DBUSERNAME', 'root'); // Database username
define('DBPASSWORD', '12345'); // Database password
define('DBNAME', 'footscape_db'); // Database name

// Connect to the database
$db = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

// Check database connection
if ($db->connect_error) {
  die("Error: Connection error - " . $db->connect_error);
}
