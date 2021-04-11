<?php
include_once '../config/database.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

if (isset($database)) {
    $connection = $database->getConnection();
    $query = "SELECT i.item_id, i.name, i.price, i.description, c.name as 'category_name' FROM `menu_items` i INNER JOIN `menu_categories` c on c.category_id = i.category_id ORDER BY c.name;";
    $result = $connection->query($query);
    $arr = array();
    while ($row = $result->fetch_assoc()) {
        $arr["$row[category_name]"][] = array(
            'id' => $row['item_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'description' => $row['description'],
        );
    }
    http_response_code(200);
    echo json_encode($arr);
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Database error'));
}