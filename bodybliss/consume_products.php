<?php
// Testing the WebService endpoint Consume

$apiUrl = "http://localhost/examples-smi/bodybliss/api/products.php"; // Webservice endpoint

// Make the request
$response = file_get_contents($apiUrl); // make GET

// Check if the response was successfully received
if ($response === FALSE) {
    die("Error accessing the webservice.");
}

// Convert JSON to PHP array
$products = json_decode($response, true);

// Display the products
echo "<h2>Available Products (via API)</h2><ul>";
foreach ($products as $product) {
    echo "<li><strong>{$product['name']}</strong> - {$product['price']}€ - Category: {$product['category']}</li>";
}
echo "</ul>";
?>
