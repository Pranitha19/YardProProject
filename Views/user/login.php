
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YardPro Login</title>
  <link rel="stylesheet" href="../../static/css/auth.css">
</head>
<body>

  <div class="auth-card">
    <!--<img src="../../static/images/logo.jpg" alt="YardPro">-->
    <h2>YardPro Login</h2>

    <?php if (!empty($_GET['err'])): ?>
      <p class="error"><?= htmlspecialchars($_GET['err']) ?></p>
    <?php elseif (!empty($_GET['msg'])): ?>
      <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <form method="post" action="../../controllers/AuthController.php?action=login">
    <label for="email"><b>Email</b></label>
      <input type="email" name="email" placeholder="Email address" required>
      <label for="password"><b>Password</b></label> 
     <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn-primary">Log in</button>
    </form>

    <a href="register.php" class="link">Don’t have an account? Register</a>
  </div>

</body>
</html>

