<?php
function checkIfUserExists ($email, $connection) {
    $query = "SELECT email FROM `users` WHERE email='$email';";
    $result = mysqli_query($connection, $query);
    return mysqli_fetch_row($result) >= 1;
}