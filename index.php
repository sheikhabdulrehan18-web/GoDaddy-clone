<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Hub | GoDaddy-Style Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <nav class="nav">
            <div class="brand">
                <span class="brand-dot"></span>
                Domain Hub
            </div>
            <div class="nav-actions">
                <?php if ($logged_in): ?>
                    <a class="btn btn-ghost" href="dashboard.php">Dashboard</a>
                    <a class="btn" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-ghost" href="login.php">Login</a>
                    <a class="btn btn-primary" href="signup.php">Create account</a>
                <?php endif; ?>
            </div>
        </nav>
 
        <section class="hero">
            <p class="pill">Premium domain manager · Secure · Fast</p>
            <h1>Find your perfect domain with a modern GoDaddy-style experience.</h1>
            <p>Search availability instantly, register in one click, and manage everything from a unified dashboard.</p>
        </section>
 
        <section class="search-card">
            <form class="search-row" id="domain-search-form">
                <input class="input" type="text" id="domain-input" name="domain" placeholder="Search domains like mybrand.com" required>
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
            <div class="status" id="status-box">
                <span id="status-text">Type a domain to check.</span>
                <span class="badge" id="status-badge"></span>
                <button class="btn btn-primary" id="register-btn" style="display:none;">Register now</button>
            </div>
        </section>
 
        <div class="stack" style="margin-top:50px;">
            <div class="card">
                <div class="topbar">
                    <h3>Why Domain Hub</h3>
                    <span class="chip">Secure sessions · Encrypted passwords</span>
                </div>
                <p class="helper">A polished, responsive control center that lets you search, register, and manage domains with premium visuals, glassmorphism strokes, and smooth transitions.</p>
            </div>
        </div>
 
        <div class="footer">Built with PHP + MySQL · GoDaddy-inspired UI</div>
    </div>
    <script src="script.js"></script>
</body>
</html>
 
 
