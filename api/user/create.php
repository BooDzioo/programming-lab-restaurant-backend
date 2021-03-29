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

$database = new Database();
$connection = $database->getConnection();

if (!checkIfUserExists($email, $connection)) {
    $query = "INSERT INTO `users` (name, surname, email, password) VALUES('$name', '$surname', '$email', '$password')";
    $result = mysqli_query($connection, $query);

    $token = createJWT($email);

    http_response_code(200);
    echo json_encode(array('token' => $token));
} else {
    http_response_code(409);
    echo json_encode(array('message' => 'User already exists!'));
}

mysqli_close($connection);

