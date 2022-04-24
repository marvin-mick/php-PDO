<?php
  session_start();

  try {
    $db = new PDO('mysql:host=YOURHOST;dbname=YOURDBNAME', 'USER', 'PASSWORD', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]);
  }

  catch (PDOException $e){

    die('Erreur : ' .$e->getMessage());
  }
?>
