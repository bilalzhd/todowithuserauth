<?php
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'todologin';

    $conn = mysqli_connect($server, $username, $password, $database);

    if(!$conn){
        echo 'Connection was not successful';
    }

?>