<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>login</title>
  <link rel="stylesheet" href="css/loginRegisterStyle.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Quicksand:wght@600&display=swap"
    rel="stylesheet">
  <script defer src="javascript/loginRegister.js" charset="utf-8"></script>
</head>

<body>

  <div class="login-wrapper">

    <form class="login" action="login.php" method="post">
      <legend class="form-legend">Accedi</legend>
      <input class="input-lg email" type="email" name="email" placeholder="E-mail" required>
      <input class="input-lg password" type="password" name="password" placeholder="Password" required>
      <input class="submit-btn" name="submit" type="submit" name="submit" value="Accedi">
    </form>

    <a class="register-link" href="register.php">Non hai ancora un account? Effettua la registrazione!</a>

    <?php

      require 'dbConnection.php';

      if(!empty($_POST['email']) && !empty($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $checkEmail="SELECT email, username, password FROM utente WHERE '$email' = email AND '$password' = password";
        $resultEmail = $conn->query($checkEmail) or die("Errore");

        //Number of rows returned by the query
        $numrowEmail=mysqli_num_rows($resultEmail);

            // Check that the user is not already registered
            if ($numrowEmail == 1) {
              $row=mysqli_fetch_array($resultEmail);
              $var_session["username"]=$row["username"];
              $var_session["password"]=$row["password"];

              $time_cookie=3600*24*7;
              setcookie("session", $var_session["username"], time()+$time_cookie);

              header("location: home.php");

            } else {
              echo '<center><p style="color:red">
              Email o password errata. Controlla i dati inseriti.</p></center>';
            }
      }
  ?>

  </div>

</body>

</html>