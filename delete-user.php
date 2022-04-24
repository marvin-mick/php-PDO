<?php require('database.php'); ?>

<?php
  if(empty($_SESSION['user'])){
  header('Location: login.php');
  }

  $user = $_SESSION['user'];

  $req = $db->prepare('DELETE FROM users WHERE id = :id');
  $req->bindValue(':id', $user->id, PDO::PARAM_INT);
  $req->execute();

  unset($_SESSION['user']);
  session_destroy();
  header('Location: inscription.php');
?>
