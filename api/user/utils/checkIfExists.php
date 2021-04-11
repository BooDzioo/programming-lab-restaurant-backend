<?php
function checkIfUserExists ($email, $connection) {
    $query = "SELECT email FROM `users` WHERE email='$email';";
    $result = $connection->query($query);
    return $result->fetch_row() >= 1;
}