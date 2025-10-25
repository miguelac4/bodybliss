<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include_once("../basic_functions.php");
require_once "../db_init.php";

e_RuntimeReport();

$conn = db_connect();

$sql = "SELECT id, name, description, price, category, image FROM products";
$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$conn->close();
?>
