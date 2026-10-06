<?php require 'config.php'; require_login();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$s = ['student_code'=>'','name'=>'','gender'=>'Male','dob'=>'','email'=>'','phone'=>'','track'=>'Science'];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([$id]);
    $s = $stmt->fetch() ?: die('Student not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $data = [
        trim($_POST['student_code']), trim($_POST['name']), $_POST['gender'],
        $_POST['dob'] ?: null, trim($_POST['email']) ?: null,
        trim($_POST['phone']) ?: null, $_POST['track'],
    ];
    if ($data[0] === '' || $data[1] === '') {
        flash('Student code and name are required.');
    } else {
        try {
            if ($id) {
                $sql = 'UPDATE students SET student_code=?, name=?, gender=?, dob=?, email=?, phone=?, track=? WHERE id=?';
                $pdo->prepare($sql)->execute([...$data, $id]);
                flash('Student updated.', 'success');
            } else {
                $sql = 'INSERT INTO students (student_code, name, gender, dob, email, phone, track) VALUES (?,?,?,?,?,?,?)';
                $pdo->prepare($sql)->execute($data);
                flash('Student added.', 'success');
            }
            header('Location: students.php'); exit;
        } catch (PDOException $ex) {
            flash($ex->getCode() == 23000 ? 'Student code already exists.' : 'Database error.');
        }
    }
    $s = array_combine(['student_code','name','gender','dob','email','phone','track'], $data);
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title><?= $id ? 'Edit' : 'Add' ?> Student</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="container" style="max-width:520px">
  <h2><?= $id ? 'Edit' : 'Add' ?> Student</h2>
  <?php show_flash(); ?>
  <form method="post" class="stack">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Student Code <input name="student_code" value="<?= e($s['student_code']) ?>" required></label>
    <label>Full Name <input name="name" value="<?= e($s['name']) ?>" required></label>
    <label>Gender
      <select name="gender">
        <?php foreach (['Male','Female'] as $g): ?>
          <option <?= $s['gender']===$g?'selected':'' ?>><?= $g ?></option>
        <?php endforeach; ?>
      </select></label>
    <label>Date of Birth <input type="date" name="dob" value="<?= e($s['dob']) ?>"></label>
    <label>Email <input type="email" name="email" value="<?= e($s['email']) ?>"></label>
    <label>Phone <input name="phone" value="<?= e($s['phone']) ?>"></label>
    <label>Track
      <select name="track">
        <?php foreach (['Science','Social'] as $t): ?>
          <option <?= $s['track']===$t?'selected':'' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select></label>
    <br>
    <button class="btn btn-green">Save</button>
    <a class="btn btn-gray" href="students.php">Cancel</a>
  </form>
</div>
</body></html>
