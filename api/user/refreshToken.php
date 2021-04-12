<?php
include_once '../config/database.php';
include_once './utils/checkCredentials.php';
include_once '../JWT/checkJWT.php';
include_once '../JWT/getTokenFromHeader.php';
include_once '../JWT/createJWT.php';
include_once './utils/checkIfAdmin.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$userId = htmlspecialchars(strip_tags($_POST['userId']));
$token = getTokenFromHeader();

if (isset($database)) {
    if (isset($userId) && isset($token)) {
        if (checkJWT($token, $userId)) {
            $connection = $database->getConnection();
            $token = createJWT($userId);
            $isAdmin = checkIfAdmin($userId, $connection);

            http_response_code(200);
            echo json_encode(array('token' => $token, 'isAdmin' => $isAdmin));
        }
    } else {
        http_response_code(422);
        echo json_encode(array('message' => 'Not enough data'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}
