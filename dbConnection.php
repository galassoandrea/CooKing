<?php

    $server="127.0.0.1";
    $username="root";
    $password="";
    $dbName="cooking";

    //Connecting to the server
    $conn=mysqli_connect($server,$username,$password,$dbName) or die("Connessione fallita");

?>
