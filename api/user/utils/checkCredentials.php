<?php




function checkUserCredentials($email, $password, $connection) {
    $query = "SELECT * FROM `users` WHERE email='$email' AND password='$password';";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_row($result) >= 1;
}