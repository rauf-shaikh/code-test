<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/registration.html');
    exit;
}

// Get and validate inputs
$firstName = trim($_POST['first_name'] ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($firstName) || empty($email) || empty($password) || $password !== $confirmPassword) {
    header('Location: ../public/registration.html?error=invalid');
    exit;
}

try {
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        header('Location: ../public/registration.html?error=email_exists');
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (first_name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$firstName, $email, $hashedPassword]);
    
    header('Location: ../public/login.html?success=registered');
    exit;

} catch (PDOException $e) {
    error_log('Registration error: ' . $e->getMessage());
    header('Location: ../public/registration.html?error=server');
    exit;
}