<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once "../basic_functions.php";
require_once "../db_init.php";
require_once "../lib/lib-mail-v2.php";

require_once "../lib/dompdf/autoload.inc.php";
use Dompdf\Dompdf;
use Dompdf\Options;

// Read JSON data received
$data = json_decode(file_get_contents("php://input"), true);

// Verify mandatory fields
$required = ['card_number', 'expirate_date', 'cvc_cvv', 'name_on_card', 'user_id'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Field '$field' dont appear."]);
        exit;
    }
}

// Hardcoded Card Validation (Use for Test)
if (
    $data['card_number'] !== '4242424242424242' ||
    $data['expirate_date'] !== '01/12' ||
    $data['cvc_cvv'] !== '424'
) {
    http_response_code(400);
    echo json_encode([
        "error" => true,
        "type" => "invalid_card",
        "message" => "Invalid card data."
    ]);
    exit;
}

// DB Connection
$conn = db_connect();
$userId = intval($data['user_id']);

// Catch User
$getUser = $conn->prepare("SELECT email, name, role FROM users WHERE id = ?");
$getUser->bind_param("i", $userId);
$getUser->execute();
$userResult = $getUser->get_result();

if ($userResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "User not found."]);
    exit;
}

$userData = $userResult->fetch_assoc();
$email = $userData['email'];
$name = $userData['name'];
$role = $userData['role'];

// Fetch cart products of a certain USERID
$sql = "SELECT p.name, p.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $products[] = $row;
}

if (empty($products)) {
    http_response_code(400);
    echo json_encode(["error" => "Carrinho está vazio."]);
    exit;
}

// Create order regist
$insertOrder = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$insertOrder->bind_param("id", $userId, $total);
if (!$insertOrder->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao registar encomenda."]);
    exit;
}
$orderId = $insertOrder->insert_id; // New order ID

// Insert products in order_items table
$insertItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
foreach ($products as $item) {
    $insertItem->bind_param(
        "isidd",
        $orderId,
        $item['name'],
        $item['quantity'],
        $item['price'],
        $item['subtotal']
    );
    $insertItem->execute();
}

// Clean user cart
$deleteCart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$deleteCart->bind_param("i", $userId);
$deleteCart->execute();


// Build Email
$toEmail = $email;
$subject = "Your checkout has been completed successfully - BodyBliss";
$emailBody = '
<html>
<head>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, sans-serif;
      color: #333;
    }
    .email-container {
      max-width: 600px;
      margin: auto;
      padding: 20px;
      border-radius: 8px;
      background-color: #f9f9f9;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    h2 {
      color: #d32f2f;
    }
    ul {
      padding-left: 20px;
    }
    li {
      margin-bottom: 6px;
    }
    .footer {
      margin-top: 20px;
      font-size: 0.9em;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <h2>Hello, ' . htmlspecialchars($name) . '!</h2>
    <p>Thank you for shopping at <strong>BodyBliss</strong>.</p>
    <p>Your order has been successfully placed with <strong>ID #' . $orderId . '</strong>.</p>
    <p>Order summary:</p>
    <ul>';


foreach ($products as $item) {
    $emailBody .= '<li>' . htmlspecialchars($item['name']) . ' x' . $item['quantity'] . ' = ' . number_format($item['subtotal'], 2) . '€</li>';
}

$emailBody .= '</ul>
    <p><strong>Total:</strong> ' . number_format($total, 2) . '€</p>
    <div class="footer">
      You will find the invoice attached.<br>
      If you have any questions, feel free to contact us.
    </div>
  </div>
</body>
</html>';

// Convert to base64
$logoPath = realpath(__DIR__ . '/../imgs/logo.jpg');
$logoData = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/jpeg;base64,' . $logoData;

// Fetch SMTP config of database
$sql = "SELECT * FROM email_accounts WHERE accountName = 'Bodybliss'";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    http_response_code(500);
    echo json_encode(["error" => "Error fetching the e-mail configurations."]);
    exit;
}

$smtpSettings = $result->fetch_assoc();

// HTML Invoice Generator
$invoiceHtml = '
<html>
<head>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #333;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .logo {
      height: 60px;
      margin-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 6px;
      text-align: left;
    }
    th {
      background-color: #f4f4f4;
    }
    .total {
      text-align: right;
      font-weight: bold;
      margin-top: 10px;
    }
    .footer {
      margin-top: 30px;
      font-size: 10px;
      text-align: center;
      color: #999;
    }
  </style>
</head>
<body>

  <div class="header">
    <img src="' . $logoSrc . '" class="logo">
    <h2>Invoice - BodyBliss</h2>
    <p>Massages & Candles</p>
  </div>

  <p><strong>Customer:</strong> ' . htmlspecialchars($name) . '<br>
     <strong>Email:</strong> ' . htmlspecialchars($email) . '<br>
     <strong>Order ID:</strong> #' . $orderId . '</p>

  <table>
    <thead>
      <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>
    </thead>
    <tbody>';


foreach ($products as $item) {
    $invoiceHtml .= '
      <tr>
        <td>' . htmlspecialchars($item['name']) . '</td>
        <td>' . $item['quantity'] . '</td>
        <td>' . number_format($item['price'], 2) . '€</td>
        <td>' . number_format($item['subtotal'], 2) . '€</td>
      </tr>';
}

$invoiceHtml .= '
    </tbody>
  </table>

  <p class="total">Total: ' . number_format($total, 2) . '€</p>

  <div class="footer">
    Thank you for shopping at BodyBliss. This invoice is valid without a signature.
  </div>
</body>
</html>';

// Build and save PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($invoiceHtml);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save temporary PDF
$pdfPath = "../uploads_pdf/invoice_order_" . $orderId . ".pdf";
file_put_contents($pdfPath, $dompdf->output());


// SMTP Parameters
$smtpSent = sendAuthEmail(
    $smtpSettings['smtpServer'],
    $smtpSettings['useSSL'] == 1,
    intval($smtpSettings['port']),
    intval($smtpSettings['timeout']),
    $smtpSettings['loginName'],
    $smtpSettings['password'],
    $smtpSettings['email'],
    $smtpSettings['displayName'],
    $toEmail,
    $subject ,
    $emailBody,
    null,           // cc
    null,           // bcc
    false,          // debug
    $pdfPath
);

// Delete PDF
if (file_exists($pdfPath)) {
    unlink($pdfPath);
}


if ($smtpSent === true) {
    echo json_encode(["success" => true, "message" => "Orders sucesefully paid and e-mail sent."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => $smtpSent]);
}
?>
