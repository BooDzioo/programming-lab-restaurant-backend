<?php
include_once '../JWT/getTokenFromHeader.php';
include_once './utils/checkIfExists.php';
include_once '../JWT/checkJWT.php';
include_once './utils/checkIfAdmin.php';
include_once '../config/database.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$token = getTokenFromHeader();
$userId = htmlspecialchars(strip_tags($_POST['userId']));
$name = htmlspecialchars(strip_tags($_POST['name']));
$surname = htmlspecialchars(strip_tags($_POST['surname']));
$email = htmlspecialchars(strip_tags($_POST['email']));
$password = htmlspecialchars(strip_tags($_POST['password']));
$accountType = htmlspecialchars(strip_tags($_POST['accountType']));

if (isset($database)) {
    if (isset($token) && isset($userId) && isset($name) && isset($surname) && isset($email) && isset($password) && isset($accountType)) {
        $connection = $database->getConnection();
        if (checkJWT($token, $userId) && checkIfAdmin($userId, $connection)) {
            if(!checkIfUserExists($email, $connection)){
                $password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO `users` (name, surname, email, password, type) VALUES ('$name', '$surname', '$email', '$password', '$accountType');";
                if ($connection->query($query)) {
                    http_response_code(200);
                }
                else {
                    http_response_code(500);
                    echo json_encode(array('message' => 'Database error'));
                }
            } else {
                http_response_code(409);
                echo json_encode(array('message' => 'User already exists'));
            }
        } else {
            http_response_code(401);
            echo json_encode(array('message' => "Unauthorized"));
        }
    } else {
        http_response_code(422);
        echo json_encode(array('message' => 'Not enough data'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}
