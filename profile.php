<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="css/profileStyle.css">
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
    $query = "SELECT fotoProfilo,nome,cognome FROM utente WHERE username = '$username'";
    $result=$conn->query($query) or die("Errore");
    $record=mysqli_fetch_row($result);
    $photo=$record[0];
    $name=$record[1];
    $surname=$record[2];
  ?>

  <nav class="navbar">
    <a href="home.php"><img class="logo" src="images/logo-nome.png" alt="logo-image"></a>
    <div class="dropdown">
      <button
        class="dropbtn"><?php echo '<p class="welcome-p">Benvenuto '.$_COOKIE["session"].'</p>'; 
                              echo '<img class="profile-picture" src="data:image/png;base64,'.base64_encode($photo).'"/>'; ?>
      </button>
      <div class="dropdown-content">
        <a href="profile.php">Il mio profilo</a>
        <a href="account.php">Account</a>
        <a class="exit" href="logout.php">Esci</a>
      </div>
    </div>

  </nav>

  <div class="cover-section">

    <?php 

      echo'<img class="profile-img" src="data:image/png;base64,'.base64_encode($photo).'" alt="profile-img">';
      echo '<h2 class="user-info">'.$name.' '.$surname.'</h2>'; 
      
    ?>

  </div>

  <div class="post-section">
    <div class="btn-container">
      <button class="switch-btn-1 active">Le tue ricette</button>
      <button class="switch-btn-2">Ricette salvate</button>
    </div>
    <div class="user-posts">

      <?php

        require 'dbConnection.php';

        // Showing recent posts
        showPost();

        if(isset($_POST['submit-comment'])) {

          writeComment();

        }

        if(isset($_POST['submit-delete'])) {

          deletePost();

        }

        function showPost() {
          require 'dbConnection.php';

          $usernameCreator=$_COOKIE["session"];

          $queryPost="SELECT * FROM post 
                      WHERE usernameUtente = '$usernameCreator' 
                      ORDER BY dataCreazione DESC, oraCreazione DESC";
          $resultPost=$conn->query($queryPost) or die("Errore");

          $numrowPost=mysqli_num_rows($resultPost);

          if($numrowPost==0) {
            echo '<p class="empty">Non hai ancora pubblicato nessuna ricetta</p>';
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
                                  WHERE username = '$usernameCreator'";
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
                          <button class="delete-btn" type="button" name="button" onclick="deletePost(this)"><i class="post-icon fas fa-trash-alt"></i></button>
                        </div>
                      </div>
                      <form class="delete-form hidden" action="profile.php" method="post">
                              <h3 class="choose">Vuoi eliminare questa ricetta?</h3>
                              <input class="post-id" type="hidden" name="id" value="'.$id.'">
                              <input class="submit-delete" type="submit" name="submit-delete" value="Elimina">
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

                          echo'<div class="comment-container hidden">
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

        function deletePost() {

          require 'dbConnection.php';
          
            $username = $_COOKIE["session"];
            $id = $_POST['id'];

            // Deleting the post data from the db
            $queryDeleteShare = "DELETE FROM post_salvato WHERE idPost = '$id'";
            $resultDeleteShare=$conn->query($queryDeleteShare);

            $queryDeleteComment = "DELETE FROM commento WHERE postId = '$id'";
            $resultDeleteComment=$conn->query($queryDeleteComment);

            $queryDeletePost = "DELETE FROM post WHERE id = '$id'";
            $resultDeletePost=$conn->query($queryDeletePost);
            echo "<meta http-equiv='refresh' content='0'>";
        }

        function deleteSavedPost() {

          require 'dbConnection.php';
          
            $username = $_COOKIE["session"];
            $id = $_POST['id'];

            // Deleting the post data from the db
            $query = "DELETE FROM post_salvato WHERE idPost = '$id'";
            $resultDelete=$conn->query($query);
            echo "<meta http-equiv='refresh' content='0'>";
        }

  ?>

    </div>
    <div class="saved-posts hidden">

      <?php

        require 'dbConnection.php';

        // Showing recent posts
        showSavedPost();

        if(isset($_POST['submit-comment'])) {

          writeComment();

        }

        if(isset($_POST['submit-delete'])) {

          deleteSavedPost();

        }

        function showSavedPost() {
          require 'dbConnection.php';

          $username=$_COOKIE["session"];

          $queryPost="SELECT * 
                      FROM post JOIN post_salvato ON id = idPost
                      WHERE usernameCondivisore = '$username' 
                      ORDER BY dataCreazione DESC, oraCreazione DESC";
          $resultPost=$conn->query($queryPost) or die("Post Error");
          $numrowPost=mysqli_num_rows($resultPost);

          if($numrowPost==0) {
            echo '<p class="empty">Non hai ancora salvato nessuna ricetta</p>';
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
              $usernameCreator =$record[7];

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
                          <button class="delete-btn" type="button" name="button" onclick="deletePost(this)"><i class="post-icon fas fa-trash-alt"></i></button>
                        </div>
                      </div>
                      <form class="delete-form hidden" action="profile.php" method="post">
                                  <h3 class="choose">Vuoi eliminare questa ricetta?</h3>
                                  <input class="post-id" type="hidden" name="id" value="'.$id.'">
                                  <input class="submit-delete" type="submit" name="submit-delete" value="Elimina">
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

                          echo'<div class="comment-container hidden">
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
  ?>

    </div>
  </div>

  <script src="javascript/profileHandler.js"></script>
  <script src="javascript/postHandler.js"></script>
</body>

</html>