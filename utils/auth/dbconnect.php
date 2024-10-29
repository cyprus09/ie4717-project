<?php
$host = 'localhost';
$dbname = 'footscape_db';
$user = 'root';
$pass = '12345';

$db = new mysqli($host, $user, $pass, $dbname);

if($db == false) {
  die("Error: connection error.".mysqli_connect_error());
}
