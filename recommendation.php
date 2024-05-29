<?php
include 'connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all product names from the cart for the current user
$cart_items_query = sqlsrv_query($con, "SELECT name FROM cart WHERE user_id = ?", array($user_id));
$cart_items = [];
while ($row = sqlsrv_fetch_array($cart_items_query, SQLSRV_FETCH_ASSOC)) {
    $cart_items[] = $row['name'];
}

// Initialize an array to store the recommendations
$recommended_products = [];

// Fetch top 4 products based on support values for each product in the cart
foreach ($cart_items as $item) {
    $sql = "SELECT consequent, support FROM apriori_rules WHERE antecedent LIKE ?";
    $params = ["%$item%"];
    $stmtRec = sqlsrv_query($con, $sql, $params);

    if ($stmtRec === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    while ($row = sqlsrv_fetch_array($stmtRec, SQLSRV_FETCH_ASSOC)) {
        $consequents = explode(', ', $row['consequent']);
        foreach ($consequents as $consequent) {
            if (!in_array($consequent, $cart_items)) {
                if (!isset($recommended_products[$consequent])) {
                    $recommended_products[$consequent] = $row['support'];
                } else {
                    // Sum the support if the same consequent is recommended multiple times
                    $recommended_products[$consequent] += $row['support'];
                }
            }
        }
    }
}

// Sort recommended products by support in descending order
arsort($recommended_products);

// Select the top 3 recommended products
$top_recommendations = array_slice(array_keys($recommended_products), 0, 3);

// Fetch details for the top recommended products
$product_details = [];
foreach ($top_recommendations as $product_name) {
    $product_details_query = sqlsrv_query($con, "SELECT name, image, price FROM product WHERE name = ?", array($product_name));
    if ($product_details_row = sqlsrv_fetch_array($product_details_query, SQLSRV_FETCH_ASSOC)) {
        $product_details[] = $product_details_row;
    }
}

// Prepare the recommendation message
$recommendation_message = "Based on your shopping preferences, we recommend these top picks for you:";

// Handle adding to cart
if(isset($_POST['add_to_cart'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Check if the item already exists in the cart
    $select_cart = sqlsrv_query($con, "SELECT * FROM cart WHERE name = ? AND user_id = ?", array($product_name, $user_id));

    if(sqlsrv_has_rows($select_cart)){
        // Update the quantity of the existing item in the cart
        $update_query = sqlsrv_query($con, "UPDATE cart SET quantity = quantity + ? WHERE name = ? AND user_id = ?", array($product_quantity, $product_name, $user_id));
        if ($update_query) {
            $message[] = 'Item successfully added to cart';
        } else {
            $message[] = 'Failed to update cart';
        }
    } else {
        // Insert a new item into the cart
        $insert_query = sqlsrv_query($con, "INSERT INTO cart (user_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?)", array($user_id, $product_name, $product_price, $product_image, $product_quantity));
        if ($insert_query) {
            $message[] = 'Item successfully added to cart';
        } else {
            $message[] = 'Failed to add item to cart';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        /* Ensure the body has no margin or padding */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Container style */
        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading style */
        .heading {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Recommendation message style */
        .recommendation-message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        /* Recommended products section */
        .recommended-products-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Recommended product item */
        .recommended-product-item {
            width: 250px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .recommended-product-item img {
            width: 100%;
            height: 200px; /* Equal height for each image */
            border-radius: 8px;
            object-fit: cover;
        }

        .recommended-product-item h3 {
            margin-top: 10px;
            font-size: 18px;
            color: #333;
        }

        .recommended-product-item p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        /* Payment section */
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
        <h1 class="heading">Checkout</h1>
        
        <!-- Recommendation Message -->
        <div class="recommendation-message">
            <p><?php echo $recommendation_message; ?></p>
        </div>

        <!-- Recommended products section -->
        <div class="recommended-products-section">
            <?php foreach ($product_details as $product): ?>
                <div class="recommended-product-item">
                    <!-- Wrap the image with an anchor tag linking to the purchase page -->
                    <a href="purchase.php">
                        <img src="admin/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    </a>
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Price: $<?php echo $product['price']; ?></p>
                    <form method="post" action="recommendation.php">
                        <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                        <input type="number" min="1" name="product_quantity" value="1">
                        <input type="submit" value="Add to Cart" name="add_to_cart" class="pay-btn">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Simplified buttons -->
        <div class="buttons-container">
            <a href="process_payment.php" class="pay-btn">Pay Now</a>
            <a href="purchase.php" class="cancel-btn">Cancel</a>
        </div>
    </div>
</body>
</html>
