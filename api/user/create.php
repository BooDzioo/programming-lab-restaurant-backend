<?php
include_once '../config/database.php';
include_once '../JWT/createJWT.php';
include_once './utils/checkIfExists.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$password = $_POST['password'];

if (isset($database)) {
    $connection = $database->getConnection();
    if (isset($name) && isset($surname) && isset($email) && isset($password)) {
        if (!checkIfUserExists($email, $connection)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO `users` (name, surname, email, password, type) VALUES('$name', '$surname', '$email', '$password', 'B')";
            $result = $connection->query($query);

            $userId = $connection->insert_id;
            $token = createJWT($userId);

            http_response_code(200);
            echo json_encode(array('token' => $token, 'userId' => $userId));
        } else {
            http_response_code(409);
            echo json_encode(array('message' => 'User already exists!'));
        }
    } else {
        http_response_code(422);
    }

} else {
    http_response_code(500);
}

