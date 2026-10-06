<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'bac_system';
$user = 'root';
$pass = '';          // change if your MySQL has a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $ex) {
    die('Database connection failed: ' . $ex->getMessage());
}

if (!function_exists('require_login')) {
    function require_login() {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }
}

if (!function_exists('e')) {
    function e($s) {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('flash')) {
    function flash($msg, $type = 'error') {
        $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
    }
}

if (!function_exists('show_flash')) {
    function show_flash() {
        if (!empty($_SESSION['flash'])) {
            $f = $_SESSION['flash'];
            unset($_SESSION['flash']);
            echo '<div class="flash ' . e($f['type']) . '">' . e($f['msg']) . '</div>';
        }
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
        return $_SESSION['csrf'];
    }
}

if (!function_exists('check_csrf')) {
    function check_csrf() {
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            die('Invalid request.');
        }
    }
}