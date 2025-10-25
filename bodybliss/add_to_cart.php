<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once("basic_functions.php");
require_once "db_init.php";

$conn = db_connect();
e_RuntimeReport();

$pid = "";

// verify if user is log in, if not show model s_m_need_login
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0){

    $user_id = $_SESSION['user_id'];  // get the current user

    if (!isset($_POST['product_id'])) {
        header("Location: index.php?pid=catalog");
        exit;
    }
    $product_id = intval($_POST['product_id']);  // get the product from form

    // Check if user already has this product in the cart
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

//  If exists = increment quantity
    if ($result->num_rows > 0) {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
//  insert new item with quantity = 1
    else {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
    // Save success message in session to trigger the add-to-cart modal
    $_SESSION["s_m_cart"] = true;
    $pid = "home";

}else{
    // Save success message in session to trigger the add-to-cart need login model
    $_SESSION["s_m_need_login"] = true;
}
include("index.php");
?>