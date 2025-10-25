<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once "../basic_functions.php";
require_once "../db_init.php";

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "user_id em falta"]);
    exit;
}

$conn = db_connect();
$userId = intval($_GET['user_id']);

$sql = "SELECT p.id, p.name, p.description, p.price, p.category, p.image, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $cartItems[] = $row;
}

echo json_encode($cartItems);
?>
