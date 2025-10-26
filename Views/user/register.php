<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YardPro Registration</title>
  <link rel="stylesheet" href="../../static/css/auth.css">
</head>
<body>
  <div class="auth-card">
    
    <!--<img src="../../static/images/logo.jpg" alt="YardPro">-->
    <h2>Create Your Account</h2>

    <?php if (!empty($_GET['err'])): ?>
      <p class="error"><?= htmlspecialchars($_GET['err']) ?></p>
    <?php endif; ?>

    <form method="post" action="../../controllers/AuthController.php?action=register">
      <label for="first_name"><b>First Name</b></label>
      <input type="text" name="first_name" placeholder="First name" required>
       <label for="last_name"><b>Last Name</b></label>
      <input type="text" name="last_name" placeholder="Last name" required>
       <label for="email"><b>Email</b></label>
      <input type="email" name="email" placeholder="Email address" required>
       <label for="password"><b>Passowrd</b></label>
      <input type="password" name="password" placeholder="Password" minlength="6" required>
       <label for="phone_no"><b>Phone number</b></label>
      <input type="text" name="phone_no" placeholder="Phone number">
      <label for="address"><b>Address</b></label>
      <input type="text" name="address" placeholder="Address">

      <button type="submit" class="btn-primary">Register</button>
    </form>

    <a href="login.php" class="link">Already have an account? Log in</a>
  </div>

</body>
</html>
