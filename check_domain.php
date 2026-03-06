<?php
header('Content-Type: application/json');
require_once 'db.php';
 
$domain = trim($_POST['domain'] ?? '');
 
if ($domain === '' || !preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $domain)) {
    echo json_encode(['available' => false, 'error' => 'Invalid domain']);
    exit;
}
 
$stmt = mysqli_prepare($conn, "SELECT id FROM domains WHERE domain_name = ?");
mysqli_stmt_bind_param($stmt, 's', $domain);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$exists = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);
 
echo json_encode(['available' => !$exists]);
?>
 
 
