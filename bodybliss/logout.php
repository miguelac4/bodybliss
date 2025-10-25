<?php
session_start();

include_once("basic_functions.php");
$conn = e_RuntimeReport();

$started = true;
$pid = "";
$_SESSION = []; // Clean all SESSION VARIABLES
session_destroy();
//include("index.php");
header("Location: index.php");
exit();
?>
