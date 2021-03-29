<?php
include_once '../config/database.php';
include_once '../JWT/createJWT.php';
include_once './utils/checkIfExists.php';
include_once './utils/checkCredentials.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$email = $_POST['email'];
$password = $_POST['password'];

$database = new Database();
$connection = $database->getConnection();

if (checkIfUserExists($email, $connection)) {
    if (checkUserCredentials($email, $password, $connection)) {
        $token = createJWT($email);

        http_response_code(200);
        echo json_encode(array('token' => $token));
    } else {
        http_response_code(401);
        echo json_encode(array('message' => 'Invalid user credentials'));
    }
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'User not found'));
}

mysqli_close($connection);