<?php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
 
$domain = '';
$error = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['domain'])) {
    $domain = trim($_GET['domain']);
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');
    if ($domain === '' || !preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $domain)) {
        $error = 'Enter a valid domain (example.com).';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM domains WHERE domain_name = ?");
        mysqli_stmt_bind_param($stmt, 's', $domain);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $exists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
 
        if ($exists) {
            $error = 'Domain already registered in our system.';
        } else {
            $status = 'active';
            $uid = $_SESSION['user_id'];
            $stmt = mysqli_prepare($conn, "INSERT INTO domains (user_id, domain_name, status, created_at) VALUES (?, ?, ?, NOW())");
            mysqli_stmt_bind_param($stmt, 'iss', $uid, $domain, $status);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Could not register domain.';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register domain | Domain Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <nav class="nav">
            <div class="brand"><span class="brand-dot"></span>Domain Hub</div>
            <div class="nav-actions">
                <a class="btn btn-ghost" href="dashboard.php">Dashboard</a>
                <a class="btn" href="logout.php">Logout</a>
            </div>
        </nav>
 
        <div class="form-card">
            <h2>Register domain</h2>
            <p class="helper">We verify availability before adding it to your account.</p>
            <?php if ($error): ?>
                <div class="status show">
                    <span><?php echo htmlspecialchars($error); ?></span>
                    <span class="badge badge-danger">ERROR</span>
                </div>
            <?php endif; ?>
            <form class="form-grid" method="POST" action="">
                <div>
                    <label for="domain">Domain name</label>
                    <input class="input" type="text" id="domain" name="domain" required placeholder="example.com" value="<?php echo htmlspecialchars($domain); ?>">
                </div>
                <button class="btn btn-primary" type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
 
 
