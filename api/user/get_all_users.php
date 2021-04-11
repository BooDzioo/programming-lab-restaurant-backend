<?php
include_once '../JWT/getTokenFromHeader.php';
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

if (isset($database)) {
    $connection = $database->getConnection();
    if (checkJWT($token, $userId) && checkIfAdmin($userId, $connection)) {
        $query = "SELECT * FROM `users`;";
        $result = $connection->query($query);
        $arr = array();
        while ($row = $result->fetch_assoc()) {
            unset($row['password']);
            array_push($arr, $row);
        }
        echo json_encode($arr);
    } else {
        http_response_code(401);
        echo json_encode(array('message' => 'Invalid credentials!'));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}

