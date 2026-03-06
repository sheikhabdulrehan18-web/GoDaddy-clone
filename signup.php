<?php
session_start();
require_once 'db.php';
 
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
 
    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
 
    if (!$errors) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = 'Email already registered.';
        }
        mysqli_stmt_close($stmt);
    }
 
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hash);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Registration failed. Try again.';
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
    <title>Create account | Domain Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <nav class="nav">
            <div class="brand"><span class="brand-dot"></span>Domain Hub</div>
            <div class="nav-actions">
                <a class="btn btn-ghost" href="index.php">Home</a>
                <a class="btn" href="login.php">Login</a>
            </div>
        </nav>
 
        <div class="form-card">
            <h2>Create your account</h2>
            <p class="helper">Secure signup with hashed passwords and validated input.</p>
            <?php if ($errors): ?>
                <div class="status show">
                    <span><?php echo htmlspecialchars(implode(' ', $errors)); ?></span>
                    <span class="badge badge-danger">ERROR</span>
                </div>
            <?php endif; ?>
            <form class="form-grid" method="POST" action="">
                <div>
                    <label for="name">Full name</label>
                    <input class="input" type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name ?? ''); ?>">
                </div>
                <div>
                    <label for="email">Email</label>
                    <input class="input" type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Create account</button>
            </form>
        </div>
    </div>
</body>
</html>
 
 
