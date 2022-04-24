<?php require('db.php'); ?>

<?php
  unset($_SESSION['user']);
  session_destroy();

  header('Location: login');
?>
