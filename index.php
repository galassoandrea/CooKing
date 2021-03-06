<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>CooKing</title>

  <link rel="stylesheet" href="css/indexStyle.css">
  <link rel="stylesheet" href="css/navbarStyle.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Quicksand:wght@600&display=swap"
    rel="stylesheet">
</head>

<body>

  <?php
    if (isset($_COOKIE["session"])) {
      header("location: home.php");
    }
  ?>

  <section id="header">

    <nav class="navbar">
      <div class="navlinks">
        <a class="Home navlink" href="#header">Home</a>
        <a class="About navlink" href="#body">About</a>
        <a class="Credits navlink" href="#footer">Credits</a>
      </div>
      <img class="logo" src="images/logo-nome.png" alt="logo-image">
      <div class="nav-btns">
        <a class="register-link" href="register.php">Registrati</a>
        <a class="login-link" href="login.php">Accedi</a>
      </div>

    </nav>

    <div class="introduction">

      <img class="big-logo" src="images/logo-nome.png" alt="logo-image">
      <h3 class="subtitle">Chiunque può essere il re della propria cucina</h3>
      <a class="big-button" href="register.php">Iscriviti Ora</a>

    </div>


  </section>

  <section id="body">

    <div class="function-container">
      <div class="function-item">
        <h3 class="function-title">Pubblica la tua ricetta</h3>
        <p class="function-par">Potrai postare ricette di qualunque tipo e condividerle con altri utenti.</p>
        <img class="food-img" src="images/food.svg" alt="food-image">

      </div>
      <div class="function-item">
        <h3 class="function-title">Riproduci piatti appetitosi</h3>
        <p class="function-par">Qualcuno ha condiviso una ricetta che ti piacerebbe riprodurre? Salvala sul tuo
          profilo!</p>
        <img class="share-img" src="images/share.svg" alt="people-image">

      </div>
      <div class="function-item">
        <h3 class="function-title">Entra a far parte della community</h3>
        <p class="function-par">Potrai interagire con altri utenti commentando i loro post.</p>
        <img class="like-img" src="images/like.svg" alt="person-image">

      </div>
    </div>

    <hr class="separator">

    <h2 class="comments">Cosa ne pensano i nostri iscritti</h2>
    <div class="comment-container">
      <div class="comment-card">
        <img class="user-image" src="images/avatar1.png" alt="user-profile-picture">
        <h3 class="username">Andrea Galasso</h3>
        <p class="user-comment">"E' fantastico avere un luogo online in cui poter condividere
          le proprie ricette con altri appassionati di cucina!"</p>
      </div>
      <div class="comment-card">
        <img class="user-image" src="images/avatar2.png" alt="user-profile-picture">
        <h3 class="username">Valerio De Savino</h3>
        <p class="user-comment">"CooKing è incredibile! Grazie agli altri utenti ho scoperto un sacco di nuove ricette."
        </p>
      </div>
      <div class="comment-card">
        <img class="user-image" src="images/avatar4.png" alt="user-profile-picture">
        <h3 class="username">Federica Verdi</h3>
        <p class="user-comment">"Cooking è il social network che mancava! E' semplicissimo e divertente da utilizzare."
        </p>
      </div>
    </div>

  </section>

  <section id="footer">
    <h1 class="footer-heading">Iscriviti ora alla community</h1>
    <a class="big-button" href="register.php">Registrati</a>
  </section>

  <section id="copyright">
    <p class="copyright-par">Cooking © 2021</p>
  </section>



</body>

</html>