<?php
// config/pdo.php
$DB_DSN  = 'mysql:host=localhost;dbname=yardpro;charset=utf8mb4';
$DB_USER = 'root';
$DB_PASS = '';

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
  exit('DB connection failed: ' . $e->getMessage());
}
