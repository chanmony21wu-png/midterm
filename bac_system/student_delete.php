<?php require 'config.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $pdo->prepare('DELETE FROM students WHERE id = ?')->execute([(int)$_POST['id']]);
    flash('Student deleted.', 'success');
}
header('Location: students.php');
