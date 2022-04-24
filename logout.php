<?php require('database.php'); ?>

<?php
  unset($_SESSION['user']);
  session_destroy();

  header('Location: login.php');
?>
