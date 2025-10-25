<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once("basic_functions.php");
$conn = e_RuntimeReport();



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


$pid = "";

//Change Profile Image to nullprofile.jpg
$sql = 'UPDATE users SET profile_pic = "nullprofile.jpg" WHERE id = ' . $_SESSION['user_id'];
$result = $conn->query($sql); //Run SQL variable

//Delete Profile Image of the directory
if($_SESSION["profile_pic"] !== "nullprofile.jpg"){
    unlink("uploads_profile/" . $_SESSION["profile_pic"]); //unlink is to delete the image from the path
}
//Change session variable
$_SESSION["profile_pic"] = "nullprofile.jpg";
$pid = "account";
include("index.php");
