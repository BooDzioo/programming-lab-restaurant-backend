<?php
function checkIfAdmin($userId, $connection) {
    $query = "SELECT type FROM `users` WHERE user_id='$userId';";
    $result = $connection->query($query);
    while ($row = $result->fetch_assoc()) {
        return $row['type'] == 'A';
    }
    return false;
}