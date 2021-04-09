<?php

function checkUserCredentials($email, $password, $connection) {
    $query = "SELECT * FROM `users` WHERE email='$email';";
    $result = $connection->query($query);
    while ($row = $result->fetch_assoc()) {
        return password_verify($password, $row['password']);
    }
}