<?php require 'config.php'; require_login();

$tracks = [
    'Science' => ['khme','Math', 'Biology', 'Chemistry', 'Physics', 'History' , 'english'],
    'Social'  => ['Khmer','Math','History', 'Geography', 'Civics','Earth', 'english'],
];
$track = $_GET['t'] ?? $_POST['track'] ?? 'Science';
if (!isset($tracks[$track])) $track = 'Science';
$subjects = $tracks[$track];

function grade_letter($avg) {
    if ($avg >= 90) return 'A';
    if ($avg >= 80) return 'B';
    if ($avg >= 70) return 'C';
    if ($avg >= 60) return 'D';
    if ($avg >= 50) return 'E';
    return 'F';
}

$stmt = $pdo->prepare('SELECT id, student_code, name FROM students WHERE track = ? ORDER BY name');
$stmt->execute([$track]);
$students = $stmt->fetchAll();

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $sid = (int)($_POST['student_id'] ?? 0);
    $action = $_POST['action'] ?? 'calculate';

    $scores = [];
    foreach ($subjects as $sub) {
        $v = (float)($_POST['score'][$sub] ?? 0);
        $scores[$sub] = max(0, min(100, $v));
    }
    $total = array_sum($scores);
    $avg = $total / count($scores);
    $result = ['total' => $total, 'avg' => $avg, 'grade' => grade_letter($avg)];

    if ($action === 'save') {
        if (!$sid) {
            flash('Please choose a student before saving to the dashboard.');
        } else {
            $up = $pdo->prepare('INSERT INTO grades (student_id, subject, score) VALUES (?,?,?)
                                 ON DUPLICATE KEY UPDATE score = VALUES(score)');
            foreach ($scores as $sub => $sc) $up->execute([$sid, $sub, $sc]);
            flash('Grades saved! They now appear on the dashboard.', 'success');
        }
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title><?= e($track) ?> Track</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="container" style="max-width:560px">
  <div class="topbar">
    <h2><?= e($track) ?> Class</h2>
    <a class="btn btn-gray" href="dashboard.php">Back</a>
  </div>
  <?php show_flash(); ?>
  <form method="post" class="stack">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="track" value="<?= e($track) ?>">
    <label>Student (required to save)
      <input type = "name" name = "student_id">
      
        <?php foreach ($students as $st): ?>
          <option value="<?= $st['id'] ?>" <?= (($_POST['student_id'] ?? 0) == $st['id']) ? 'selected' : '' ?>>
            <?= e($st['student_code'] . ' - ' . $st['name']) ?></option>
        <?php endforeach; ?>
      </label>
    <?php foreach ($subjects as $sub): ?>
      <label><?= e($sub) ?> (0-100) 
        <input type="number" step="0.01" min="0" max="100" name="score[<?= e($sub) ?>]"
               value="<?= e($_POST['score'][$sub] ?? '') ?>" required></label>
    <?php endforeach; ?>
    <br>
    <button class="btn btn-blue" name="action" value="calculate">Calculate</button>
    <button class="btn btn-green" name="action" value="save">Save to Dashboard</button>
  </form>

  <?php if ($result): ?>
  <div class="result">
    <b>Total:</b> <?= number_format($result['total'], 2) ?><br>
    <b>Average:</b> <?= number_format($result['avg'], 2) ?><br>
    <b>Grade:</b> <?= $result['grade'] ?>
  </div>
  <?php endif; ?>
</div>
</body></html>
