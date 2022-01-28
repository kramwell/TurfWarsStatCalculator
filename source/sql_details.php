<?php


$DBServer = 'localhost';
 $DBUser   = 'root';
 $DBPass   = '';

$conn = new mysqli($DBServer, $DBUser, $DBPass);

// check connection
if ($conn->connect_error) {
  trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}	

?>