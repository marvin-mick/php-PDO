<?php require('database.php'); ?>

<?php
  if(!empty($_SESSION['user'])){
  header('Location: dashboard.php');
  }

  if(!empty($_POST)){
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    extract($post);
    $errors = [];

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
      array_push($errors, "L'adresse email n'est pas valide.");
    }

    if(empty($password)){
      array_push($errors, "Le mot de passe est requis.");
    }

    if(empty($errors)){
      $req = $db->prepare('SELECT * FROM users WHERE email = :email');
      $req->bindValue(':email', $email, PDO::PARAM_STR);
      $req->execute();

      $user = $req->fetch();
      if($user && password_verify($password, $user->password)){
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
      }

      array_push($errors, "Mauvais identifiants.");
    }
  }
?>
