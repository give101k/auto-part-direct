<?php
$dsn = 'mysql:host=localhost;dbname=hanlonbra_AutoDirect';
$username = 'hanlobra_hanlobr';
$password = 'vAr7mry$$404AvU';

try {
  $db = new PDO($dsn, $username, $password);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
  $error_message = $e->getMessage();
  include 'database_error.php';
  exit();
}
?>