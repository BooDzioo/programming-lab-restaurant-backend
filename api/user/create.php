<?php
include_once '../config/database.php';
include_once '../JWT/createJWT.php';
include_once './utils/checkIfExists.php';
include_once './utils/checkIfAdmin.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

$name = strip_tags($_POST['name']);
$surname = strip_tags($_POST['surname']);
$email = strip_tags($_POST['email']);
$password = strip_tags($_POST['password']);

if (isset($database)) {
    $connection = $database->getConnection();
    if (isset($name) && isset($surname) && isset($email) && isset($password)) {
        if (!checkIfUserExists($email, $connection)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO `users` (name, surname, email, password, type) VALUES('$name', '$surname', '$email', '$password', 'B')";
            $result = $connection->query($query);

            $userId = $connection->insert_id;
            $token = createJWT($userId);
            $isAdmin = checkIfAdmin($userId, $connection);

            http_response_code(200);
            echo json_encode(array('token' => $token, 'userId' => $userId, 'isAdmin' => $isAdmin));
        } else {
            http_response_code(409);
            echo json_encode(array('message' => 'User already exists!'));
        }
    } else {
        http_response_code(422);
        echo json_encode(array('message' => 'Not enough data'));
    }

} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}

