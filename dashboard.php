<?php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
 
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
 
// Fetch domains
$domains = [];
$stmt = mysqli_prepare($conn, "SELECT domain_name, status, created_at FROM domains WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $dname, $dstatus, $dcreated);
while (mysqli_stmt_fetch($stmt)) {
    $domains[] = [
        'domain_name' => $dname,
        'status' => $dstatus,
        'created_at' => $dcreated
    ];
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Domain Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <nav class="nav">
            <div class="brand"><span class="brand-dot"></span>Domain Hub</div>
            <div class="nav-actions">
                <span class="pill"><?php echo htmlspecialchars($user_email); ?></span>
                <a class="btn" href="logout.php">Logout</a>
            </div>
        </nav>
 
        <div class="stack" style="margin-top:30px;">
            <div class="card">
                <div class="topbar">
                    <div>
                        <h2>Hello, <?php echo htmlspecialchars($user_name); ?></h2>
                        <p class="helper">Manage your registered domains and add new ones instantly.</p>
                    </div>
                    <a class="btn btn-primary" href="index.php">Search new domain</a>
                </div>
                <form class="search-row" method="POST" action="register_domain.php" style="margin-top:10px;">
                    <input class="input" type="text" name="domain" placeholder="Register a new domain like example.com" required>
                    <button class="btn btn-primary" type="submit">Register</button>
                </form>
                <p class="helper">Domain availability is validated before registration.</p>
            </div>
 
            <div class="card">
                <div class="topbar">
                    <h3>Your domains</h3>
                    <span class="chip"><?php echo count($domains); ?> total</span>
                </div>
                <?php if ($domains): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Status</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($domains as $d): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($d['domain_name']); ?></td>
                                    <td><span class="badge badge-success"><?php echo htmlspecialchars(ucfirst($d['status'])); ?></span></td>
                                    <td><?php echo htmlspecialchars($d['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="helper">No domains yet. Register your first domain above.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
 
 
