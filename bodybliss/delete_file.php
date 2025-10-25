<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("basic_functions.php");
require_once "db_init.php";

// Connect to the database
$conn = db_connect();

// Runtime Errors Report
e_RuntimeReport();

$pid="";
$pid="repository";
// Check if 'file' parameter is provided
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = "uploads_repository/" . $fileName;

    if (file_exists($filePath)) {
        // Delete File
        if (unlink($filePath)) {
            echo "File deleted successfully!<br>";
        } else {
            echo "Error: Unable to delete file.<br>";
            $pid="repository";
        }
    } else {
        echo "Error: File not found.<br>";
        $pid="repository";
    }

    // Delete file record from database
    $sql = "DELETE FROM repository WHERE file_name = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $fileName);
        if ($stmt->execute()) {
            header("Location: index.php?pid=. $pid");
        } else {
            echo "Error: Could not delete database entry.";
            header("Location: index.php?pid=. $pid");

        }
        $stmt->close();
    } else {
        echo "Error: Failed to prepare SQL statement.";
        header("Location: index.php?pid=. $pid");
    }
} else {
    echo "Error: No file specified.";
    header("Location: index.php?pid=. $pid");
}
?>
