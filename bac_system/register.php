<?php require 'config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
check_csrf();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || strlen($password) < 4) {
    flash('Username required and password must be at least 4 characters.');
} else {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        flash('Username already taken.');
    } else {
        $ins = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $ins->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        flash('Registered successfully. Please log in.', 'success');
    }
}
header('Location: index.php');
