<?php
session_start();
require_once __DIR__ . '/../config/pdo.php';

// helper: redirect with message
function back($url, $param, $msg) {
  header('Location: ' . $url . '?' . $param . '=' . urlencode($msg));
  exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = trim($_POST['first_name'] ?? '');
  $last  = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $phone = trim($_POST['phone_no'] ?? '');
  $addr  = trim($_POST['address'] ?? '');

  if ($first === '' || $last === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
    back('/YardProProject/views/user/register.php', 'err', 'Invalid input');
  }

  // unique email check
  $chk = $pdo->prepare("SELECT 1 FROM users WHERE email = :email LIMIT 1");
  $chk->execute([':email' => $email]);
  if ($chk->fetch()) {
    back('/YardProProject/views/user/register.php', 'err', 'Email already registered');
  }

  $hash = password_hash($pass, PASSWORD_DEFAULT);

  $ins = $pdo->prepare("
    INSERT INTO users (first_name, last_name, email, password_hash, phone_no, address)
    VALUES (:f, :l, :e, :h, :p, :a)
  ");
  $ins->execute([
    ':f' => $first, ':l' => $last, ':e' => $email, ':h' => $hash,
    ':p' => $phone ?: null, ':a' => $addr ?: null
  ]);

  back('/YardProProject/views/user/login.php', 'msg', 'Registered successfully. Please log in.');
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  
  $admin_email = 'admin@yardpro.com';
  $admin_hashed_password = '$2y$10$akXgyv5KTJ5e6Yi.26Svz.orpww7zfAfUn15K8V58/984ZDQYswkO'; 
  // This hash corresponds to plain text password "Admin@123"

  if ($email === $admin_email && password_verify($pass, $admin_hashed_password)) {
    $_SESSION['admin'] = true;
    $_SESSION['role'] = 'admin';
    header('Location: /YardProProject/views/admin/home.php');
    exit;
  }


  $sel = $pdo->prepare("SELECT user_id, first_name, password_hash FROM users WHERE email = :email");
  $sel->execute([':email' => $email]);
  $u = $sel->fetch();

  if (!$u || !password_verify($pass, $u['password_hash'])) {
    back('/YardProProject/views/user/login.php', 'err', 'Invalid credentials');
  }

  // set session
  $_SESSION['user_id']    = (int)$u['user_id'];
  $_SESSION['first_name'] = $u['first_name'];

  // optional: visit tracking (user_visits)
  $sid = session_id();
  setcookie('yardproProject_sid', $sid, time()+86400*30, '/', '', false, true);

  $vs = $pdo->prepare("SELECT visit_id FROM user_visits WHERE session_id=:sid AND user_id=:uid");
  $vs->execute([':sid' => $sid, ':uid' => $u['user_id']]);
  if ($vs->fetch()) {
    $pdo->prepare("UPDATE user_visits SET visit_count = visit_count + 1 WHERE session_id=:sid AND user_id=:uid")
        ->execute([':sid'=>$sid, ':uid'=>$u['user_id']]);
  } else {
    $pdo->prepare("INSERT INTO user_visits (user_id, session_id, visit_count) VALUES (:uid, :sid, 1)")
        ->execute([':uid'=>$u['user_id'], ':sid'=>$sid]);
  }

  header('Location: /YardProProject/views/user/home.php');
  exit;
}

// fallback
header('Location: /YardProProject/index.php');
