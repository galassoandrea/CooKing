<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>register</title>
  <link rel="stylesheet" href="css/loginRegisterStyle.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Quicksand:wght@600&display=swap"
    rel="stylesheet">
</head>

<body>

  <div class="register-wrapper">

    <form name="register" class="register" action="register.php" method="post" onsubmit="return validateRegistration()" enctype="multipart/form-data">
      <legend class="form-legend">Registrati</legend>
      <div class="flex-container">
        <div class="data-container">
          <input class="input-rg name" type="text" name="name" placeholder="Nome">
          <input class="input-rg surname" type="text" name="surname" placeholder="Cognome" >
          <input class="input-rg username" type="text" name="username" placeholder="Nome Utente" >
          <input class="input-rg email" type="email" name="email" placeholder="E-mail" >
          <input class="input-rg password" type="password" name="password" placeholder="Password" >
        </div>
        <div class="photo-container">
          <label for="file-upload" class="custom-file-input"><i class="far fa-image"></i>
            Scegli un'immagine del profilo
          </label>
          <input id="file-upload" class="file-upload" type="file" name="file" accept="image/png, image/jpeg"  />
          <img class="hidden" id="target" />
          <p class="empty">Nessuna immagine selezionata al momento.</p>
        </div>
      </div>
      <input class="submit-btn" type="submit" name="submit" value="Registrati">
    </form>

    <a class="login-link" href="login.php">Hai già un account? Effettua il login!</a>

    <?php

      require 'dbConnection.php';

      if(!empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password'])) {
        
        if(getimagesize($_FILES['file']['tmp_name'])==FALSE) {
          echo "Seleziona una foto";
        } else {
          $image = $_FILES['file']['tmp_name'];
          $image = addslashes(file_get_contents($image));

          $name = $_POST['name'];
          $surname = $_POST['surname'];
          $username = $_POST['username'];
          $email = $_POST['email'];
          $password = $_POST['password'];

          $checkUsername="SELECT username FROM utente WHERE '$username' = username";
          $checkEmail="SELECT email FROM utente WHERE '$email' = email";
          $resultUsername = $conn->query($checkUsername) or die("Errore");
          $resultEmail = $conn->query($checkEmail) or die("Errore");

          //Number of rows returned by the query
          $numrowUsername=mysqli_num_rows($resultUsername);
          $numrowEmail=mysqli_num_rows($resultEmail);
            // Check that the user is not already registered
            if ($numrowUsername != 0) {
              echo '<p class="warning">Username già esistente.</p>';
            } else if ($numrowEmail != 0){
              echo '<p class="warning">E-mail già esistente.</p>';
            } else {
              $reg="INSERT INTO utente (nome, cognome, email, username, password, fotoProfilo) VALUES ('$name', '$surname', '$email', '$username', '$password','$image')";
              $resultreg=$conn->query($reg);
              if($resultreg) {
                $message = "Registrazione effettuata con successo. Effettua il login.";
                echo "<script type='text/javascript'>
                      alert('$message');
                      window.location = 'login.php';
                      </script>";  
              }
            }
          
        }
      }
  ?>

  </div>

</body>

<script src="javascript/loginRegisterHandler.js"></script>
<script src="javascript/imageHandler.js"></script>

</html>