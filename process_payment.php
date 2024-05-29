<?php
include 'connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Fetch cart items for the current user
    $cart_query = sqlsrv_query($con, "SELECT * FROM cart WHERE user_id = ?", array($user_id));
    if ($cart_query === false) {
        echo "Failed to fetch cart items. Please try again later.";
        exit();
    }
    
    $cart_items = [];
    $total_price = 0;
    while ($row = sqlsrv_fetch_array($cart_query, SQLSRV_FETCH_ASSOC)) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }

    // Insert order into the database
    $order_query = sqlsrv_query($con, "INSERT INTO orders (user_id, name, location, total_price, card_number, expiry_date, cvv) OUTPUT INSERTED.id VALUES (?, ?, ?, ?, ?, ?, ?)", array($user_id, $name, $location, $total_price, $card_number, $expiry_date, $cvv));
    if ($order_query === false) {
        echo "Failed to place the order. Please try again later.";
        exit();
    }
    
    if ($order_row = sqlsrv_fetch_array($order_query, SQLSRV_FETCH_ASSOC)) {
        $order_id = $order_row['id'];

        // Insert each cart item into the order_items table
        foreach ($cart_items as $item) {
            $item_query = sqlsrv_query($con, "INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)", array($order_id, $item['name'], $item['price'], $item['quantity']));
            if ($item_query === false) {
                echo "Failed to place the order. Please try again later.";
                exit();
            }
        }

        // Clear the cart for the user
        $clear_cart_query = sqlsrv_query($con, "DELETE FROM cart WHERE user_id = ?", array($user_id));
        if ($clear_cart_query === false) {
            echo "Failed to place the order. Please try again later.";
            exit();
        }

        echo "Order placed successfully!";
    } else {
        echo "Failed to place the order. Please try again.";
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Process Payment</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }

            .container {
                max-width: 1200px;
                margin: 50px auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .heading {
                color: #333;
                text-align: center;
                margin-bottom: 20px;
            }

            .payment-section {
                margin-top: 20px;
            }

            .payment-section label {
                display: block;
                margin-bottom: 10px;
            }

            .payment-section input[type=text] {
                width: calc(100% - 20px);
                padding: 10px;
                font-size: 16px;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .buttons-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 20px;
            }

            .pay-btn,
            .cancel-btn {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                border-radius: 4px;
            }

            .cancel-btn {
                background-color: #f44336;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="heading">Enter Payment Details</h1>

            <!-- Payment Form -->
            <form action="process_payment.php" method="post" class="payment-section">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
                <label for="card_number">Credit Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" required>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
                <div class="buttons-container">
                    <input type="submit" value="Pay Now" class="pay-btn">
                    <a href="purchase.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
