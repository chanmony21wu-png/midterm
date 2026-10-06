<?php require 'config.php'; require_login(); ?>
<?php require 'config.php'; require_login();

function grade_letter($avg) {
    if ($avg >= 90) return 'A';
    if ($avg >= 80) return 'B';
    if ($avg >= 70) return 'C';
    if ($avg >= 60) return 'D';
    if ($avg >= 50) return 'E';
    return 'F';
}

$rows = $pdo->query(
    "SELECT s.id, s.student_code, s.name, s.track,
            SUM(g.score) AS total, AVG(g.score) AS avg,
            GROUP_CONCAT(CONCAT(g.subject, ': ', g.score) ORDER BY g.id SEPARATOR ', ') AS detail
     FROM students s
     JOIN grades g ON g.student_id = s.id
     GROUP BY s.id, s.student_code, s.name, s.track
     ORDER BY s.name"
)->fetchAll();
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Student Dashboard</title>
<link rel="stylesheet" href="style.css"></head>
<body>
  <div class="hero">
    <h1>Student Dashboard</h1>
    <div>Choose a class track to calculate grades.</div>
    <div class="badge">Joined by <b><?= e($_SESSION['username']) ?></b> on <?= e($_SESSION['joined']) ?></div><br>
    <a class="btn btn-white" href="logout.php">Logout</a>
    <a class="btn btn-green" href="students.php">View User Data</a>
  </div>
  <div class="tracks">
    <a class="track science" href="track.php?t=Science">
      <b>Science Class</b></a>
    <a class="track social" href="track.php?t=Social">
      <b>Social Class</b></a>
  </div>


  <div class="container" style="margin-top:0">
    <h2>Saved Grades</h2>
    <table>
      <tr><th>Code</th><th>Name</th><th>Track</th><th>Scores</th><th>Total</th><th>Average</th><th>Grade</th></tr>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= e($r['student_code']) ?></td>
        <td><?= e($r['name']) ?></td>
        <td><?= e($r['track']) ?></td>
        <td><?= e($r['detail']) ?></td>
        <td><?= number_format($r['total'], 2) ?></td>
        <td><?= number_format($r['avg'], 2) ?></td>
        <td><b><?= grade_letter($r['avg']) ?></b></td>
      </tr>
      <?php endforeach; if (!$rows): ?>
      <tr><td colspan="7">No grades saved yet. Pick a track, choose a student, and click Save to Dashboard.</td></tr>
      <?php endif; ?>
    </table>
  </div>
</body></html>
