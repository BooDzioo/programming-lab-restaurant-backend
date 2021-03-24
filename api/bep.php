<?php
include_once './config/database.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

echo json_encode(array("message" => "asdasd", "name" => $_POST["name"]));


//$database = new Database();
//$connection = $database->getConnection();
//
//$sql = "INSERT INTO `users` VALUES('$_POST['name']', '$_POST['surname']')"





