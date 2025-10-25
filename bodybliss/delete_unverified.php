<?php

// To clean database manually (debug): http://localhost/examples-smi/bodybliss/delete_unverified.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("basic_functions.php");
$conn = e_RuntimeReport();

require_once "db_init.php";
$conn = db_connect();


$sql = "DELETE FROM users
        WHERE is_verified = 0 
          AND created_at < (NOW() - INTERVAL 7 DAY)";


// Only to test with a smaller time
/*
$sql = "DELETE FROM users
        WHERE is_verified = 0
          AND created_at < (NOW() - INTERVAL 1 MINUTE)";
*/

if ($conn->query($sql) === TRUE) {
    echo "Old unverified accounts deleted successfully!";
} else {
    echo "Error deleting records: " . $conn->error;
}

$conn->close();
?>