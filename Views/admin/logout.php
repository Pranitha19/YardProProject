<?php
session_start();
require_once '../../helpers/flash.php';

setFlash('success', 'Logged out successfully.');

session_unset();
session_destroy();

header('Location: login.php');
exit();
