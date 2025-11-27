<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YardPro Login</title>
  <link rel="stylesheet" href="/YardProProject/Static/css/auth.css">
</head>
<body>

  <div class="auth-card">
    <h2>YardPro</h2>
    <h3>Enter your login credentials</h3>

    <?php if (!empty($_GET['err'])): ?>
      <p class="error"><?= htmlspecialchars($_GET['err']) ?></p>
    <?php elseif (!empty($_GET['msg'])): ?>
      <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <form method="post" action="/YardProProject/Controllers/AuthController.php?action=login">
      <label>Email:</label>
      <input type="email" name="email" placeholder="Enter your Email" required>

      <label>Password:</label>
      <input type="password" name="password" placeholder="Enter your Password" required>

      <button type="submit" class="btn-primary">Login</button>
    </form>

    <a href="register.php" class="link">Not registered? Create an account</a>
  </div>

</body>
</html>
