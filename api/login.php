<?php
header("Access-Control-Allow-Origin: *");

include_once './config/database.php';

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

echo json_encode(array("message" => $_POST['name']));

if(isset($_POST['name']) && isset($_POST['surname'])) {
$name = $_POST['name'];
$surname = $_POST['surname'];

$host = "localhost";
$username = "root";
$db_name = "project_restaurant";
$password = "";

$connect = mysqli_connect($host, $username, $password, $db_name);

mysqli_select_db($connect, $db_name);

$sql = "INSERT INTO `users` (`name`, `surname`) values($name, $surname)";
//$sql = "INSERT INTO `users` (`name`, `surname`) values('janusz', 'chuj')";
if (!mysqli_query($connect, $sql)) {
    echo mysqli_error($connect);
}

mysqli_close($connect);

http_response_code(200);

}

echo $_POST['name'];

echo json_encode(array(
    'test' => true
));

