<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: /YardProProject/views/user/login.php?msg=Logged+out');
exit;
