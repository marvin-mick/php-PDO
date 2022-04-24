<?php require('db.php'); ?>

<?php
  if(empty($_SESSION['user'])){
  header('Location: login');
  }

  $user = $_SESSION['user'];
  $filePath = 'photos/'.$user->id.'/'.$user->photo;

  $dir = new DirectoryIterator(dirname('photos/'.$user->id));

  foreach ($dir as $fileinfo){
    if($fileinfo->isDot()){
      unlink($fileinfo->getPathname());
    }
  }

  if($user->photo && file_exists($filePath) && is_file($filePath)){
    unlink($filePath);
    rmdir('photos/'.$user->id);
  }

  $req = $db->prepare('DELETE FROM users WHERE id = :id');
  $req->bindValue(':id', $user->id, PDO::PARAM_INT);
  $req->execute();

  unset($_SESSION['user']);
  session_destroy();
  header('Location: inscription');
?>
