<?php
session_start();
include_once("basic_functions.php");
require_once "db_init.php";
$conn = db_connect();

$user_id = $_SESSION['user_id'];
$updates = [];
$pid = "";

$country_code = $_POST['country_code'] ?? '';
$phone_number = $_POST['phone'] ?? '';
$full_phone = '';

if (!empty($phone_number)) {
    $full_phone = $country_code . $phone_number;
    $updates[] = "phone = '" . $conn->real_escape_string($full_phone) . "'";
    $_SESSION["phone"] = $full_phone;
}

if (!empty($_POST['country'])) {
    $country = $conn->real_escape_string($_POST['country']);
    $updates[] = "country = '$country'";
    $_SESSION["country"] = $country;
}

// Avatar
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $old_filename = basename($_FILES['profile_pic']['name']);
    $fileType = pathinfo($old_filename, PATHINFO_EXTENSION);
    $filename = "p$user_id." . $fileType;
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array(strtolower($fileType), $allowedTypes)) {
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads_profile/$filename")) {
            $updates[] = "profile_pic = '$filename'";
            $_SESSION["profile_pic"] = $filename;
        } else {
            echo "Error updating Image.<br>";
        }
    }
}

// Update in DB only if have alterations
if (!empty($updates)) {
    $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = $user_id";
    if ($conn->query($sql)) {
        $_SESSION['profile_updated'] = true;
//        $pid = "account";
    } else {
        $_SESSION['profile_update_error'] = true;
//        $pid = "account";
    }
} else {
    $_SESSION['profile_update_error'] = true;
//    $pid = "account";
}

header("Location: index.php?pid=account");
exit();
