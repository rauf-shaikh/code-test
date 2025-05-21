<?php
require_once __DIR__ . '/db.php';

// Initialize secure session
session_start();
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header('Location: ../public/login.html?error=invalid');
        exit;
    }

    // Check credentials
    try {
        $stmt = $pdo->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT']; // Security check
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR']; // Additional security
            
            header('Location: ../public/dashboard.php');
            exit;
        } else {
            header('Location: ../public/login.html?error=invalid');
            exit;
        }
    } catch (PDOException $e) {
        error_log('Login error: ' . $e->getMessage());
        header('Location: ../public/login.html?error=server');
        exit;
    }
}

// If not POST request, redirect to login
header('Location: ../public/login.html');
exit;