<?php
// Shared database connection using mysqli
// Credentials provided in the requirements
$db_host = 'localhost';
$db_user = 'rsk9_03';
$db_pass = '123456';
$db_name = 'rsk9_03';
 
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
 
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
 
// Ensure proper charset
mysqli_set_charset($conn, 'utf8mb4');
?>
 
 
