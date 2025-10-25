<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("basic_functions.php");
require_once "db_init.php";

$conn = db_connect();
e_RuntimeReport();

$pid = "";

// Get user_id from POST (sent by Admin form)
if (isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];

    // Fetch the current profile picture filename
    $sql = "SELECT profile_pic FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_pic = $row['profile_pic'];

        // Delete file from server (only if not nullprofile.jpg)
        if (!empty($profile_pic) && $profile_pic !== "nullprofile.jpg") {
            $filePath = "uploads_profile/" . $profile_pic;
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }

        // Update database to set profile_pic = 'nullprofile.jpg'
        $sqlUpdate = "UPDATE users SET profile_pic = 'nullprofile.jpg' WHERE id = $user_id";
        if ($conn->query($sqlUpdate) === TRUE) {
            // Success
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "User not found.";
    }
}

// Redirect back to Admin Zone
$pid = "adminzone";
include("index.php");
exit();
?>
