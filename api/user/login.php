<?php
include_once '../config/database.php';
include_once '../JWT/createJWT.php';
include_once './utils/checkIfExists.php';
include_once './utils/checkCredentials.php';
include_once './utils/getId.php';
include_once './utils/checkIfAdmin.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$email = htmlspecialchars(strip_tags($_POST['email']));
$password = htmlspecialchars(strip_tags($_POST['password']));

if (isset($database)) {
    $connection = $database->getConnection();
    if (isset($email) && isset($password)) {
        if (checkIfUserExists($email, $connection)) {
            if (checkUserCredentials($email, $password, $connection)) {
                $userId = getUserId($email, $connection);
                $token = createJWT($userId);
                $isAdmin = checkIfAdmin($userId, $connection);

                http_response_code(200);
                echo json_encode(array('token' => $token, 'userId' => $userId, 'isAdmin' => $isAdmin));
            }
            else {
                http_response_code(401);
                echo json_encode(array('message' => 'Invalid user credentials'));
            }
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'User not found'));
        }
    } else {
        http_response_code(422);
        echo json_encode(array('message' => 'Not enough data'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}

