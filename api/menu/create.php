<?php
include_once '../JWT/getTokenFromHeader.php';
include_once '../config/database.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$userId = htmlspecialchars(strip_tags($_POST['userId']));
$token = getTokenFromHeader();

if (isset($database)) {
    if (isset($token) && isset($userId)) {
        if (checkJWT($token, $userId)) {
            $connection = $database->getConnection();
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