<?php require 'config.php';
if (!empty($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f1f5f9;
    }

    .card {
      width: 100%;
      max-width: 360px;
      background: #fff;
      padding: 36px 30px;
      border-radius: 14px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
      text-align: center;
    }

    h1 {
      font-size: 26px;
      color: #0f172a;
      margin-bottom: 6px
    }

    .sub {
      font-size: 14px;
      color: #64748b;
      margin-bottom: 24px
    }

    input {
      width: 100%;
      padding: 13px 14px;
      margin-bottom: 12px;
      font-size: 15px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      outline: none;
      transition: .2s;
    }

    input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, .15)
    }

    .btns {
      display: flex;
      gap: 10px;
      margin-top: 6px
    }

    button {
      flex: 1;
      padding: 12px;
      font-size: 15px;
      font-weight: 600;
      border-radius: 8px;
      cursor: pointer;
      transition: .2s
    }

    .login {
      background: #2563eb;
      color: #fff;
      border: 0
    }

    .login:hover {
      background: #1d4ed8
    }

    .register {
      background: #fff;
      color: #2563eb;
      border: 1px solid #2563eb
    }

    .register:hover {
      background: #eff6ff
    }

    .flash {
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 14px
    }

    .flash.error {
      background: #fee2e2;
      color: #991b1b
    }

    .flash.success {
      background: #dcfce7;
      color: #166534
    }
  </style>
</head>

<body>
  <div class="card">
    <h1>Form Login</h1>
    <p class="sub">Enter username and password</p>
    <?php show_flash(); ?>
    <form method="post" action="login.php">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <input type="text" name="username" placeholder="Username" required autofocus>
      <input type="password" name="password" placeholder="Password" required>
      <div class="btns">
        <button type="submit" class="login">Login</button>
        <button type="submit" class="register" formaction="register.php">Register</button>
      </div>
    </form>
  </div>
</body>

</html>