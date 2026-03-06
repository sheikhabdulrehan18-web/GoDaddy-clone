<?php
session_start();
require_once 'db.php';
 
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
 
    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email.';
    }
 
    if (!$errors) {
        $stmt = mysqli_prepare($conn, "SELECT id, name, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $uid, $uname, $hash);
        if (mysqli_stmt_fetch($stmt) && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $uid;
            $_SESSION['user_name'] = $uname;
            $_SESSION['user_email'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Domain Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <nav class="nav">
            <div class="brand"><span class="brand-dot"></span>Domain Hub</div>
            <div class="nav-actions">
                <a class="btn btn-ghost" href="index.php">Home</a>
                <a class="btn" href="signup.php">Create account</a>
            </div>
        </nav>
 
        <div class="form-card">
            <h2>Welcome back</h2>
            <p class="helper">Login to manage your domains securely.</p>
            <?php if ($errors): ?>
                <div class="status show">
                    <span><?php echo htmlspecialchars(implode(' ', $errors)); ?></span>
                    <span class="badge badge-danger">ERROR</span>
                </div>
            <?php endif; ?>
            <form class="form-grid" method="POST" action="">
                <div>
                    <label for="email">Email</label>
                    <input class="input" type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
 
 
