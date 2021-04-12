<?php
include_once '../JWT/getTokenFromHeader.php';
include_once '../config/database.php';
include_once './utils/checkIfAdmin.php';
include_once '../JWT/checkJWT.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$userId = $_POST['userId'];
$requestedUser = $_POST['requestedUser'];
$token = getTokenFromHeader();

if (isset($database)) {
    if (isset($userId) && isset($token) && isset($requestedUser)) {
        $connection = $database->getConnection();
        if (checkIfAdmin($userId, $connection) && checkJWT($token, $userId)) {
            if($userId !== $requestedUser) {
                $query = "DELETE FROM `users` WHERE user_id='$requestedUser';";
                if ($connection->query($query) && $connection->affected_rows == 1) {
                    http_response_code(200);
                } else {
                    http_response_code(500);
                    echo json_encode(array('message' => 'Database error'));
                }
            } else {
                http_response_code(401);
                echo json_encode(array('message' => 'You cant delete yourself'));
            }
        } else {
             http_response_code(401);
             echo json_encode(array('message' => 'Invalid credentials'));
         }
    } else {
        http_response_code(422);
        echo json_encode(array('message' => 'Not enough data'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}

