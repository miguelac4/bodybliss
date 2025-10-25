<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("basic_functions.php");
$conn = e_RuntimeReport();

$status = "error";
$message = "An unexpected error occurred.";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    require_once "db_init.php";
    $conn = db_connect();

    $sql = "SELECT * FROM users WHERE verify_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $updateSql = "UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $row['id']);
        if ($stmt->execute()) {
            $status = "success";
            $message = "Your email has been verified successfully!";
        } else {
            $message = "Error updating verification.";
        }
    } else {
        $message = "Invalid or expired token.";
    }
} else {
    $message = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            text-align: center;
        }
        .icon-success {
            font-size: 4rem;
            color: #28a745;
        }
        .icon-error {
            font-size: 4rem;
            color: #dc3545;
        }
        .btn-go {
            background-color: #c91f5b;
            color: white;
        }
        .btn-go:hover {
            background-color: #a31648;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="mb-4">
        <?php if ($status === "success"): ?>
            <i class="bi bi-check-circle-fill icon-success"></i>
            <h3 class="mt-3">Email Verified!</h3>
        <?php else: ?>
            <i class="bi bi-x-circle-fill icon-error"></i>
            <h3 class="mt-3">Verification Failed</h3>
        <?php endif; ?>
    </div>
    <p class="text-muted"><?= htmlspecialchars($message) ?></p>
    <a href="index.php?pid=login" class="btn btn-go mt-3 px-4">Go to Login</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
