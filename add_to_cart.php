<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_name = $_POST['product_name'];
$quantity = $_POST['quantity'];

// Check if the product is already in the cart for the user
$sql_check = "SELECT COUNT(*) as count FROM cart WHERE user_id = ? AND name = ?";
$params_check = array($user_id, $product_name);
$stmt_check = sqlsrv_query($con, $sql_check, $params_check);
$row_check = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

if ($row_check['count'] > 0) {
    // If the product is already in the cart, update the quantity
    $sql_update = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND name = ?";
    $params_update = array($quantity, $user_id, $product_name);
    $stmt_update = sqlsrv_query($con, $sql_update, $params_update);

    if ($stmt_update === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    }
