<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Account</title>
  <link rel="stylesheet" href="css/navbarStyle.css">
  <link rel="stylesheet" href="css/accountStyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Quicksand:wght@600&display=swap"
    rel="stylesheet">
  <script src="https://kit.fontawesome.com/f586a3b164.js" crossorigin="anonymous"></script>
</head>

<body>

  <?php

      require 'dbConnection.php';

      // Loading username and avatar of the user logged
      $username = $_COOKIE["session"];
      $query = "SELECT fotoProfilo FROM utente WHERE username = '$username'";
      $result=$conn->query($query) or die("Errore");
      $record=mysqli_fetch_row($result);
      $photo=$record[0];

    ?>

  <nav class="navbar">
    <a href="home.php"><img class="logo" src="images/logo-nome.png" alt="logo-image"></a>
    <div class="dropdown">
      <button
        class="dropbtn"><?php echo '<p class="welcome-p">Benvenuto '.$_COOKIE["session"].'</p>'; 
                                      echo '<img class="profile-picture" src="data:image/png;base64,'.base64_encode($photo).'"/>'; ?></button>
      <div class="dropdown-content">
        <a href="profile.php">Il mio profilo</a>
        <a href="account.php">Account</a>
        <a class="exit" href="logout.php">Esci</a>

      </div>
    </div>

  </nav>

  <div class="personal-info">

    <h3 class="info-heading">Informazioni personali</h3>
    <hr class="separator">

    <?php

        require 'dbConnection.php';

        $username = $_COOKIE["session"];
        $query = "SELECT * FROM utente WHERE username = '$username'";
        $result=$conn->query($query) or die("Errore");
        $record=mysqli_fetch_row($result);

        $name = $record[0];
        $surname = $record[1];
        $email = $record[2];
        $username = $record[3];
        $password = $record[4];

        // Displaying personal information
        echo '<p class="info"><span>Nome: </span>'.$name.'</p>';
        echo '<p class="info"><span>Cognome: </span>'.$surname.'</p>';
        echo '<p class="info"><span>Email: </span>'.$email.'</p>';
        echo '<p class="info"><span>Nome Utente: </span>'.$username.'</p>';
        echo "<button class='pass'>Reimposta Password</button>";
        echo "<button class='delete'>Elimina Profilo</button>";

      ?>

    <!-- Form for the change of password -->
    <form class="change-pass hidden" action="account.php" method="post" onsubmit="return validatePassword()">
      <p class="pass-info">Inserisci la password corrente</p>
      <input class="password" type="password" name="old-pass" required>
      <p class="pass-info">Inserisci la nuova password</p>
      <input class="password" type="password" name="new-pass" required>
      <input class="submit-pass" type="submit" name="submit-pass" value="Conferma Password">
    </form>

    <!-- Form for the account deleting -->
    <form class="delete-account hidden" action="account.php" method="post" onsubmit="return validateDelete()">
      <p class="pass-info">Inserisci la tua password</p>
      <input class="password" type="password" name="password" required>
      <p class="warning">Attenzione: Cliccando elimina il tuo account sarà cancellato definitivamente!</p>
      <input class="submit-delete" type="submit" name="submit-delete" value="Elimina">
    </form>

    <?php

      require 'dbConnection.php';

      if(isset($_POST['submit-pass'])) {

        if(!empty($_POST['old-pass']) && !empty($_POST['new-pass'])) {

          $username = $_COOKIE["session"];
          $query = "SELECT password FROM utente WHERE username = '$username'";
          $result=$conn->query($query) or die("Errore");
          $record=mysqli_fetch_row($result);

          $password = $record[0];

          $oldPass = $_POST['old-pass'];
          $newPass = $_POST['new-pass'];

          // Checking if the current pass corresponds to the pass inserted by the user
          if($oldPass == $password) {

            // Updating the password
            $query = "UPDATE utente SET password = '$newPass' WHERE username = '$username'";
            $result=$conn->query($query) or die("Errore");
            if($result) {
              $message = "Password modificata con successo.";
              echo "<script type='text/javascript'>alert('$message');</script>";
            }
          } else {
              $message = "La password inserita è errata.";
              echo "<script type='text/javascript'>alert('$message');</script>";
          }
        }
      }

      if(isset($_POST['submit-delete'])) {

        if(!empty($_POST['password'])) {

          $username = $_COOKIE["session"];
          $query = "SELECT password FROM utente WHERE username = '$username'";
          $result=$conn->query($query) or die("Errore");
          $record=mysqli_fetch_row($result);

          $password = $record[0];

          $currentPass = $_POST['password'];

          // Checking if the current pass corresponds to the pass inserted by the user
          if($currentPass == $password) {

            // Deleting the account
            $queryDeleteComment = "DELETE FROM commento WHERE usernameCommentor = '$username'";
            $queryDeleteShare = "DELETE FROM post_salvato WHERE usernameCondivisore = '$username'";
            $queryDeletePost = "DELETE FROM post WHERE usernameUtente = '$username'";
            $queryDeleteUser = "DELETE FROM utente WHERE username = '$username'";
            $resultComment=$conn->query($queryDeleteComment) or die("Error1");
            $resultShare=$conn->query($queryDeleteShare) or die("Error2");
            $resultPost=$conn->query($queryDeletePost) or die("Error3");
            $resultUser=$conn->query($queryDeleteUser) or die("Error4");
            if($resultUser) {
              $message = "Account eliminato con successo";
              echo "<script type='text/javascript'>
                          alert('$message');
                          window.location = 'logout.php';
                    </script>";            
            }
          } else {
              $message = "La password inserita è errata.";
              echo "<script type='text/javascript'>alert('$message');</script>";
          }
        }
      }

      ?>

  </div>

  <script src="javascript/accountHandler.js" charset="utf-8"></script>
</body>

</html>