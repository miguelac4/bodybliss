<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("basic_functions.php");
$conn = e_RuntimeReport();

//Include Debugs Functions
//(difference "include_once"/"required_once":
//if the php file dosnt exist the required give an error)



$started = true;
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user inputs
    $email = $_POST["email"];
    $password = $_POST["password"];

    //Database Credentials Catch: Manual Way__________
    //$servername = "localhost";
    //$username = "root";
    //$password_db = "";
    //$dbname = "bodybliss";

    // Create connection
    //$conn = new mysqli($servername, $username, $password_db, $dbname, 3306);
    // Check connection
    //if ($conn->connect_error) {
        //die("Connection failed: " . $conn->connect_error);
    //}
    //________________________________________________

    //Database Credentials Catch: XML Way_____________
    require_once "db_init.php";
    $conn = db_connect();
    //________________________________________________

    $sql = "SELECT id, password, name, email, role, country, phone, profile_pic, is_verified FROM users WHERE email LIKE '$email'";
    $result = $conn->query($sql);

    $error = "";

    // output data of each row
    if ($row = $result->fetch_assoc()) {
        // Verify password with function that use the hash password
        if (password_verify($password, $row["password"])) {
            // If user already verified email
            if ($row["is_verified"] == 1) {
                $pid = "account";
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["country"] = $row["country"];
                $_SESSION["phone"] = $row["phone"];
                $_SESSION["profile_pic"] = $row["profile_pic"];
            }else{
                $_SESSION['email_not_verified'] = true;
                $pid = "login";
            }
        } else {
            $_SESSION['login_error'] = true;
            $pid = "login";
        }

    } else {
        $_SESSION['login_error'] = true;
        $pid = "login";
    }
}

include("index.php");
?>

