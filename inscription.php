<?php require('database.php'); ?>

<?php
  if(!empty($_SESSION['user'])){
  header('Location: dashboard');
  }

  $title = "Inscription";

  if(!empty($_POST)){
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    extract($post);
    $errors = [];

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

    if(empty($telephone) || strlen($telephone) != 10){
      array_push($errors, "Merci de saisir un numéro de téléphone valide.");
    }

    if(empty($address)){
      array_push($errors, "Merci de saisir une adresse.");
    }

    if(empty($zipcode) || strlen($zipcode) != 5){
      array_push($errors, "Merci de saisir un code postal à 5 chiffres.");
    }

    if(empty($city)){
      array_push($errors, "Merci de saisir une ville.");
    }

    if(empty($password) || strlen($password) < 6){
      array_push($errors, "Le mot de passe doit contenir au moins 6 charactères.");
    }

    if(empty($errors)){
      $req = $db->prepare('SELECT * FROM users WHERE name = :name');
      $req->bindValue(':name', $name, PDO::PARAM_STR);
      $req->execute();

      if($req->rowCount() > 0){
        array_push($errors, "Un utilisateur est déjà enregistré avec ce nom d'utilisateur.");
      }

      $req = $db->prepare('SELECT * FROM users WHERE email = :email');
      $req->bindValue(':email', $email, PDO::PARAM_STR);
      $req->execute();

      if($req->rowCount() > 0){
        array_push($errors, "Un utilisateur est déjà enregistré avec cet email.");
      }

        if(empty($errors)){
          $req = $db->prepare('INSERT INTO users (civility, firstname, lastname, name, email, telephone, address, zipcode, city, password, created_at) VALUES (:civility, :firstname, :lastname, :name, :email, :telephone, :address, :zipcode, :city, :password, NOW())');
          $req->bindValue(':civility', $civility, PDO::PARAM_STR);
          $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
          $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
          $req->bindValue(':name', $name, PDO::PARAM_STR);
          $req->bindValue(':email', $email, PDO::PARAM_STR);
          $req->bindValue(':telephone', $telephone, PDO::PARAM_INT);
          $req->bindValue(':address', $address, PDO::PARAM_STR);
          $req->bindValue(':zipcode', $zipcode, PDO::PARAM_INT);
          $req->bindValue(':city', $city, PDO::PARAM_STR);
          $req->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
          $req->execute();

          unset($civility, $firstname, $lastname, $name, $email, $telephone, $address, $zipcode, $city, $password);
          $success = "Ton inscription est terminée, tu peux te connecter.";
        }
    }
}
?>
