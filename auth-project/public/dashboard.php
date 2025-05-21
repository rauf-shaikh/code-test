<?php
require_once __DIR__ . '/../php/db.php';

// Secure session start
session_start();

// Authentication check
if (!isset($_SESSION['user_id']) || 
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] ||
    $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    
    session_destroy();
    header('Location: /auth-project/public/login.html');
    exit;
}

// Get user data safely
$firstName = htmlspecialchars($_SESSION['first_name'] ?? 'User', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* [Keep your existing dashboard CSS] */
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?= $firstName ?>!</h1>
        
        <!-- Secure logout form with CSRF token -->
        <form action="../php/logout.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)) ?>">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</body>
</html>