<?php
include_once '../config/database.php';
include_once './utils/checkCredentials.php';
include_once '../JWT/checkJWT.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$token = $_POST['token'];
$userId = $_POST['userId'];
$email = $_POST['email'];

if(isset($database)) {
    if (isset($oldPassword) && isset($newPassword) && isset($token) && isset($userId) && isset($email)) {
        if (checkJWT($token, $userId)) {
            $connection = $database->getConnection();
            if (checkUserCredentials($email, $oldPassword, $connection)) {
                $password = password_hash($newPassword, PASSWORD_DEFAULT);
                $query = "UPDATE `users` SET password='$password' WHERE user_id='$userId';";
                $result = $connection->query($query);
                if ($connection->affected_rows === 1) {
                    http_response_code(200);
                } else {
                    echo $connection->affected_rows;
                    http_response_code(500);
                }
            } else {
                http_response_code(401);
            }
        } else {
            http_response_code(401);
        }
    } else {
        http_response_code(422);
    }
} else {
    http_response_code(500);
}
