<?php
include_once '../JWT/getTokenFromHeader.php';
include_once '../config/database.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$userId = $_POST['userId'];
$token = getTokenFromHeader();

if (isset($database)) {
    if (isset($token) && isset($userId)) {
        if (checkJWT($token, $userId)) {
            $connection = $database->getConnection();
        } else {
            http_response_code(401);
        }
    } else {
        http_response_code(422);
    }
} else {
    http_response_code(500);
}