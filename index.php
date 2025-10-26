
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YardPro - Lawn & Landscaping</title>
  <link rel="stylesheet" href="static/css/style.css">
</head>
<body>

  <!-- Top Navbar -->
  <header class="navbar">
    <div class="logo">
    <!--<a href="/YardProProject/index.php" class="logo-link">-->
      <img src="/YardProProject/static/images/logo.jpg" alt="YardPro Logo" class="logo-img">
      <span class="logo-text">YardPro</span>
    <!-- </a>--> 
    </div>
    <div class="nav-buttons">
      <a href="views/user/register.php" class="btn">Register</a>
      <a href="views/user/login.php" class="btn btn-login">Login</a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Welcome to <span>YardPro</span></h1>
      <p>Your trusted partner for professional lawn and landscaping services.</p>
    </div>
  </section>

</body>
</html>

