<?php
// Database connection parameters
define('DBSERVER', 'localhost');
define('DBUSERNAME', 'root');
define('DBPASSWORD', '12345');
define('DBNAME', 'footscape_db');

// Connect to the database and set it as a global variable
$db = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

// Check for a connection error
if ($db->connect_error) {
  die("Error: Connection error - " . $db->connect_error);
}

// Select the database
if (!$db->select_db(DBNAME)) {
  die("Unable to locate the footscape db!");
}
