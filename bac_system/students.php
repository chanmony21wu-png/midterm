<?php require 'config.php'; require_login();
$students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll(); ?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Students</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="topbar">
    <h2>All Students</h2>
    <div>
      <a class="btn btn-green" href="student_form.php">+ Add Student</a>
      <a class="btn btn-gray" href="dashboard.php">Dashboard</a>
    </div>
  </div>
  <?php show_flash(); ?>
  <table>
    <tr><th>ID</th><th>Code</th><th>Name</th><th>Gender</th><th>DOB</th><th>Email</th><th>Phone</th><th>Track</th><th>Actions</th></tr>
    <?php foreach ($students as $s): ?>
    <tr>
      <td><?= $s['id'] ?></td>
      <td><?= e($s['student_code']) ?></td>
      <td><?= e($s['name']) ?></td>
      <td><?= e($s['gender']) ?></td>
      <td><?= e($s['dob']) ?></td>
      <td><?= e($s['email']) ?></td>
      <td><?= e($s['phone']) ?></td>
      <td><?= e($s['track']) ?></td>
      <td>
        <a class="btn btn-blue" href="student_form.php?id=<?= $s['id'] ?>">Edit</a>
        <form method="post" action="student_delete.php" style="display:inline"
              onsubmit="return confirm('Delete this student?')">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" value="<?= $s['id'] ?>">
          <button class="btn btn-red">Delete</button>
        </form>
      </td>
    </tr>
    <?php endforeach; if (!$students): ?>
    <tr><td colspan="9">No students yet.</td></tr>
    <?php endif; ?>
  </table>
</div>
</body></html>
