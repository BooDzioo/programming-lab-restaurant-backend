<?php
function getUserId ($email, $connection) {
    $query = "SELECT user_id from `users` WHERE email='$email';";
    $result = $connection->query($query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['user_id'];
    } else {
        echo "0 results";
    }
}