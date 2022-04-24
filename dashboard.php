<?php require('database.php'); ?>

<?php
  if(empty($_SESSION['user'])){
  header('Location: login.php');
  }
?>

<?php
  $user = $_SESSION['user'];

  if(!empty($_POST['submit'])){
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    extract($post);
    $errors = [];

    if(empty($civility)){
      array_push($errors, "Une civilité doit être sélectionée.");
    }

    if(empty($firstname) || strlen($firstname) < 2){
      array_push($errors, "Le prénom doit contenir au moins 2 charactères.");
    }

    if(empty($lastname) || strlen($lastname) < 2){
      array_push($errors, "Le nom doit contenir au moins 2 charactères.");
    }

    if(empty($name) || strlen($name) < 3){
      array_push($errors, "Le nom d'utilisateur doit contenir au moins 3 charactères.");
    }

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
      array_push($errors, "L'adresse email n'est pas valide.");
    }

    if(empty($address)){
      array_push($errors, "Merci de saisir une adresse.");
    }

    if(empty($telephone) || strlen($telephone) != 10){
      array_push($errors, "Merci de saisir un numéro de téléphone valide.");
    }

    if(empty($zipcode) || strlen($zipcode) < 5){
      array_push($errors, "Merci de saisir un code postal à 5 chiffres.");
    }

    if(empty($city)){
      array_push($errors, "Merci de saisir une ville.");
    }

    if(empty($errors)){
      $req = $db->prepare('SELECT * FROM users WHERE name = :name AND id != :id');
      $req->bindValue(':name', $name, PDO::PARAM_STR);
      $req->bindValue(':id', $user->id, PDO::PARAM_INT);
      $req->execute();

      if($req->rowCount() > 0){
        array_push($errors, "Un autre utilisateur a déjà ce nom.");
      }

      $req = $db->prepare('SELECT * FROM users WHERE email = :email AND id != :id');
      $req->bindValue(':email', $email, PDO::PARAM_STR);
      $req->bindValue(':id', $user->id, PDO::PARAM_INT);
      $req->execute();

      if($req->rowCount() > 0){
          array_push($errors, "Un autre utilisateur a déjà cet email.");
      }

        if(empty($errors)){
          $req = $db->prepare('SELECT * FROM users WHERE id = :id');
          $req->bindValue(':id', $user->id, PDO::PARAM_INT);
          $req->execute();

          $user = $req->fetch();

          $req = $db->prepare('UPDATE users SET civility = :civility, firstname = :firstname, lastname = :lastname, name = :name, email = :email, telephone = :telephone, address = :address, zipcode = :zipcode, city = :city WHERE id = :id');
          $req->bindValue(':civility', $civility, PDO::PARAM_STR);
          $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
          $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
          $req->bindValue(':name', $name, PDO::PARAM_STR);
          $req->bindValue(':email', $email, PDO::PARAM_STR);
          $req->bindValue(':telephone', $telephone, PDO::PARAM_INT);
          $req->bindValue(':address', $address, PDO::PARAM_STR);
          $req->bindValue(':zipcode', $zipcode, PDO::PARAM_INT);
          $req->bindValue(':city', $city, PDO::PARAM_STR);
          $req->bindValue(':id', $user->id, PDO::PARAM_INT);
          $req->execute();

          $user = $req->fetch();

          unset($_SESSION['user']);
          $_SESSION['user'] = $user;

          $success = "Tes informations ont été mises à jour.";
        }
     }
  }
?>
