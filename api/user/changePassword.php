<?php
include_once '../config/database.php';
include_once './utils/checkCredentials.php';
include_once '../JWT/checkJWT.php';
include_once '../JWT/getTokenFromHeader.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$oldPassword = htmlspecialchars(strip_tags($_POST['oldPassword']));
$newPassword = htmlspecialchars(strip_tags($_POST['newPassword']));
$token = getTokenFromHeader();
$userId = htmlspecialchars(strip_tags($_POST['userId']));
$email = htmlspecialchars(strip_tags($_POST['email']));

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
                    echo json_encode(array('message' => 'Database error'));
                }
            } else {
                http_response_code(401);
                echo json_encode(array('message' => 'Invalid user credentials'));
            }
        } else {
            http_response_code(401);
            echo json_encode(array('message' => 'Invalid user credentials'));
        }
    } else {
        http_response_code(422);
        echo json_encode(array('message' => 'Not enough data'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}
