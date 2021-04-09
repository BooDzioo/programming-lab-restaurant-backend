<?php
include_once '../config/database.php';
include_once '../JWT/checkJWT.php';
include_once './utils/checkIfExists.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$token = $_POST['token'];
$userId = $_POST['userId'];

if(isset($database) && isset($userId) && isset($token)) {
    if(checkJWT($token, $userId)) {
        $connection = $database->getConnection();
        $query = "SELECT name, surname, email FROM `users` WHERE user_id='$userId';";
        $result = $connection->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode($row);
                http_response_code(200);
            }
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'mysqli problem'));
        }
    }   else {
        http_response_code(401);
        echo json_encode(array('message' => "Unauthorized"));
    }
} else {
    echo json_encode(array('message' => 'not set', 'database' => isset($database), 'userId' => isset($userId), 'token' => isset($token)));
}

