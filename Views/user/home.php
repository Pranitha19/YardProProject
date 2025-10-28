<?php require_once __DIR__ . '/../../session_guard.php';
?>
<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Home</title>
</head>

<body>
    <h2>Welcome, < ?=htmlspecialchars( $_SESSION[ 'first_name' ] ?? 'User' ) ?>!</h2>
    <p>This is your dashboard.</p>
    <p><a href='/YardProProject/views/user/logout.php'>Logout</a></p>
</body>
</html>