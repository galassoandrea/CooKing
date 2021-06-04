<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Home</title>
  <link rel="stylesheet" href="css/homeStyle.css">
  <link rel="stylesheet" href="css/postStyle.css">
  <link rel="stylesheet" href="css/navbarStyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Quicksand:wght@600&display=swap"
    rel="stylesheet">
  <script src="https://kit.fontawesome.com/f586a3b164.js" crossorigin="anonymous"></script>
</head>

<body>

  <?php
    // Loading username and photo of the user logged
    require 'dbConnection.php';

    $username = $_COOKIE["session"];    
    $query = "SELECT fotoProfilo FROM utente WHERE username = '$username'";
    $result=$conn->query($query) or die("Errore");
    $record=mysqli_fetch_row($result);
    $photo=$record[0];
  ?>

  <nav class="navbar">
    <a href="home.php"><img class="logo" src="images/logo-nome.png" alt="logo-image"></a>
    <div class="navlinks">
      <a class="new-post navlink"><i class="nav-icon fas fa-plus-circle"></i></a>
    </div>
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

  <!-- Form for creating a new post -->
  <form name="post" class="post hidden" action="home.php" onsubmit="return validatePost()" method="post"
    enctype="multipart/form-data">
    <legend class="intro">Crea un post</legend>
    <hr class="separator">
    <textarea class="title" rows="1" name="title" placeholder="Cosa hai preparato oggi?"></textarea>
    <textarea class="description" rows="1" cols="100" name="ingredients" wrap="hard"
      placeholder="Quali ingredienti hai usato?"></textarea>
    <textarea class="description" rows="1" cols="100" name="description" wrap="hard"
      placeholder="Scrivi qui il procedimento"></textarea>
    <label for="file-upload" class="custom-file-input"><i class="far fa-image"></i>
      Scegli un immagine
    </label>
    <input id="file-upload" class="file-upload" type="file" name="file" accept="image/png, image/jpeg" required />
    <img id="target" />
    <input class="submit" type="submit" name="submit-post" value="Crea">
  </form>

  <?php

      require 'dbConnection.php';

      // Showing recent posts
      showPost();

      if(isset($_POST['submit-post'])) {

        writePost();

      }

      if(isset($_POST['submit-comment'])) {

        writeComment();

      }

      if(isset($_POST['submit-share'])) {

        sharePost();

      }

      function showPost() {
        require 'dbConnection.php';

        $username = $_COOKIE["session"];   

        $queryPost="SELECT * FROM post ORDER BY dataCreazione DESC, oraCreazione DESC";
        $resultPost=$conn->query($queryPost) or die("Errore");

        $numrowPost=mysqli_num_rows($resultPost);

        if($numrowPost==0) {
          echo "La query non ha prodotto risultati <br>";
        } else {

          for($i=0;$i<$numrowPost;$i++) {

            $record=mysqli_fetch_row($resultPost);

            $id=$record[0];
            $title=$record[1];
            $content=$record[2];
            $image=$record[3];
            $ingredients=$record[4];
            $date=$record[5];
            $time=$record[6];
            $usernameCreator=$record[7];

            // Limitating post content string to 300 characters
            $shortContent=substr($content, 0, 300);

            // Loading post comments
            $queryComment="SELECT usernameCommentor,commento
                          FROM commento WHERE postId = '$id'";
            $resultComment=$conn->query($queryComment) or die("Comment Error");
            $numrowComment=mysqli_num_rows($resultComment);

            // Loading the photo of the user who made the post
            $queryPhotoMaker="SELECT fotoProfilo
                           FROM utente JOIN post on username = usernameUtente
                           WHERE id = '$id'";
            $resultPhotoMaker=$conn->query($queryPhotoMaker) or die("Maker's photo Error");
            $recordPhotoMaker=mysqli_fetch_row($resultPhotoMaker);
            $userPhotoMaker=$recordPhotoMaker[0];

            // Loading the photo of logged user
            $queryPhotoLogged="SELECT fotoProfilo
                           FROM utente
                           WHERE username = '$username'";
            $resultPhotoLogged=$conn->query($queryPhotoLogged) or die("User's photo Error");
            $recordPhotoLogged=mysqli_fetch_row($resultPhotoLogged);
            $userPhotoLogged=$recordPhotoLogged[0];
            
            // Printing the post structure
            echo '<div class="post-container">
                    <div class="post-header">
                      <img class="profile-picture" src="data:image/png;base64,'.base64_encode($userPhotoMaker).'"/>
                      <p class="header-p">'.$usernameCreator.' ha condiviso un post</p>
                    </div>
                    <div class="post-body">
                      <div class="body-divider-l">
                        <h3 class="post-name">'.$title.'</h3>
                        <h3 class="ingredients">Ingredienti</h3>
                        <p class="ing-list">'.nl2br($ingredients).'</p>
                      </div>
                      <div class="vl"></div>
                      <div class="body-divider-r">
                        <h3 class="procedure">Preparazione</h3>
                        <div>
                          <p class="post-content hidden">'.$content.'<span class="switch-content" onclick="showMore(this)">Mostra meno</span></p>
                          <p class="post-content">'.$shortContent.'...<span class="switch-content" onclick="showMore(this)">Mostra altro</span></p>
                        </div>
                        <div><img class="post-image" src="data:image/jpeg;base64,'.base64_encode($image).'"/></div>
                      </div>

                    </div>
                    <div class="post-footer">
                      <div class="btn-container">
                        <button class="comment-btn" type="button" name="button" onclick="showComments(this)"><i class="post-icon fas fa-comment-dots"></i></button>
                        <button class="share-btn" type="button" name="button" onclick="sharePost(this)"><i class="post-icon fas fa-share"></i></button>
                      </div>
                    </div>
                    <form class="share-form hidden" action="home.php" method="post">
                      <h3 class="choose">Vuoi salvare questa ricetta sul tuo profilo?</h3>
                      <input class="post-id" type="hidden" name="id" value="'.$id.'">
                      <input class="submit-share" type="submit" name="submit-share" value="Salva">
                    </form>';

              if($numrowComment==0) {
                echo '<div class="comment-section hidden">
                        <form class="new-comment" action="home.php" method="post">
                        <img class="profile-picture" src="data:image/jpeg;base64,'.base64_encode($userPhotoLogged).'"/>
                        <input class="write-comment" type="text" name="comment" placeholder="Scrivi un commento">
                          <input class="post-id" type="hidden" name="id" value="'.$id.'">
                          <input class="submit-comment" type="submit" name="submit-comment" value="Invia">
                        </form>
                      </div>';
              } else {

                echo'<div class="comment-section hidden">';

                for($j=0;$j<$numrowComment;$j++) {

                  $recordComment=mysqli_fetch_row($resultComment);

                  $unameCommenter=$recordComment[0];
                  $commentContent=$recordComment[1];

                  // Loading the photo of the user who commented the post
                  $queryPhotoComment="SELECT fotoProfilo
                                      FROM utente
                                      WHERE username = '$unameCommenter'";
                  $resultPhotoComment=$conn->query($queryPhotoComment) or die("Commenter's photo Error");
                  $recordPhotoComment=mysqli_fetch_row($resultPhotoComment);
                  $userPhotoComment=$recordPhotoComment[0];

                        echo'<div class="comment-container">
                                <img class="profile-picture" src="data:image/jpeg;base64,'.base64_encode($userPhotoComment).'"/>
                                <div class="comment-divider">
                                  <h4 class="commentor">'.$unameCommenter.'</h4>
                                  <p class="comment">'.$commentContent.'</p>
                                </div>
                              </div>';
                }
                echo'<form class="new-comment" action="home.php" method="post">
                      <img class="profile-picture" src="data:image/jpeg;base64,'.base64_encode($userPhotoLogged).'"/>
                      <input class="write-comment" type="text" name="comment" placeholder="Scrivi un commento">
                        <input class="post-id" type="hidden" name="id" value="'.$id.'">
                        <input class="submit-comment" type="submit" name="submit-comment" value="Invia">
                      </form>
                    </div>';
              }
              echo '</div>';
          }
       } 
        
      }

      function writePost() {

        require 'dbConnection.php';

        if(!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['ingredients'])) {

          $title = $_POST['title'];
          $ingredients = $_POST['ingredients'];
          $description = $_POST['description'];
          $username = $_COOKIE["session"];
          $current_date = date("Y/m/d");
          $current_time = date("h:i:sa");
  
            if(getimagesize($_FILES['file']['tmp_name'])==FALSE) {
              echo " error ";
            } else {
              $image = $_FILES['file']['tmp_name'];
              $image = addslashes(file_get_contents($image));

              $titl = mysqli_real_escape_string($conn, $title);
              $desc = mysqli_real_escape_string($conn, $description);
              $ing = mysqli_real_escape_string($conn, $ingredients);
  
              // Adding the post data to the db
              $query = "INSERT INTO post (id,titolo,contenuto,immagine,ingredienti,dataCreazione,oraCreazione,usernameUtente)
                        VALUES (NULL,'$titl','$desc','$image','$ing','$current_date','$current_time','$username')";
              $resultPost=$conn->query($query);
              if(!$resultPost) {
                echo 'Errore';
              } else {
                echo "<meta http-equiv='refresh' content='0'>";
              }

            }
  
          }
      }

      function writeComment() {

        require 'dbConnection.php';

        if(!empty($_POST['comment'])) {
        
          $username = $_COOKIE["session"];
          $comment = $_POST['comment'];
          $id = $_POST['id'];
  
            // Adding the comment data to the db
            $query = "INSERT INTO commento (usernameCommentor,postId,commento)
                      VALUES ('$username','$id','$comment')";
            $resultComment=$conn->query($query);
            echo "<meta http-equiv='refresh' content='0'>";

          }
      }

      function sharePost() {

        require 'dbConnection.php';
        
          $username = $_COOKIE["session"];
          $id = $_POST['id'];
  
          // Adding the comment data to the db
          $query = "INSERT INTO post_salvato (usernameCondivisore,idPost)
                      VALUES ('$username','$id')";
          $resultShare=$conn->query($query);

      }

?>

  <script src="javascript/postHandler.js" charset="utf-8"></script>

</body>

</html>