<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once("basic_functions.php");
require_once "db_init.php";

$conn = db_connect();
e_RuntimeReport();

$pid = "";

$uploadDir = "uploads_repository/";

// Check if file was uploaded
if (isset($_FILES['repository_pic']) && $_FILES['repository_pic']['error'] == 0) {
    $old_filename = basename($_FILES['repository_pic']['name']); //catch old name
    $fileType = pathinfo($old_filename, PATHINFO_EXTENSION); //catch file extension (jpg, png, etc)
    $filename = "r".$_SESSION['user_id']."_" . uniqid() . "." . $fileType;  //concatenate all the name


    // Allowed file types (optional)
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];


    if (in_array(strtolower($fileType), $allowedTypes)) {
        // Move the uploaded file to the server directory
        //move_uploaded_file is to add the image to the path
        if (move_uploaded_file($_FILES['repository_pic']['tmp_name'], $uploadDir . $filename)) {

            //Add to table Repository into the database
            $sql = "INSERT INTO repository (user_id, file_name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $_SESSION["user_id"], $filename);
            $pid = "account";

            if($stmt->execute()) {
                $_SESSION["repository_pic"] = $filename;
                $pid = "repository";
            }

        } else {
            echo "Error uploading your file.";
            $pid = "repository";
        }
    } else {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $pid = "repository";
    }
} else {
    echo "No file uploaded or an error occurred.";
    $pid = "repository";
}

include("index.php");
?>
