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

// Select the top four products based on support values
$recommended_products_query = sqlsrv_query($con, "SELECT name, COUNT(*) as support FROM cart GROUP BY name ORDER BY support DESC");
$top_products = [];
while ($product = sqlsrv_fetch_array($recommended_products_query, SQLSRV_FETCH_ASSOC)) {
    $top_products[] = $product['name'];
}

// Exclude products that are already in the user's cart
$recommended_product_names = array_diff($top_products, $cart_items);

// Fetch details for the top three recommended products
$recommended_products = [];
$counter = 0;
foreach ($recommended_product_names as $product_name) {
    if ($counter >= 3) {
        break;
    }
    $product_details_query = sqlsrv_query($con, "SELECT name, image, price FROM product WHERE name = ?", array($product_name));
    if ($product_details = sqlsrv_fetch_array($product_details_query, SQLSRV_FETCH_ASSOC)) {
        $recommended_products[] = $product_details;
        $counter++;
    }
}

// Prepare the recommendation message
$recommendation_message = "Based on your shopping preferences, we recommend these top picks for you:";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    
    <link rel="stylesheet" href="checkout.css">


   
</head>
<body>
    <div class="container">
        <h1 class="heading">Checkout</h1>

      
<!-- Recommendation Message -->
<div class="recommendation-message">
    <p><?php echo $recommendation_message; ?></p>
    <?php foreach ($recommended_products as $product): ?>
        <div class="recommended-product-item">
            <!-- Wrap the image with an anchor tag linking to the purchase page -->
            <a href="purchase.php">
                <img src="admin/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            <p><?php echo $product['name']; ?></p>
            <p>Price: <?php echo $product['price']; ?></p>
        </div>
    <?php endforeach; ?>
</div>


        <style>
/* Ensure the body has no margin or padding */
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

/* Style for the recommendation message */
.recommendation-message {
    width: 100%;
    text-align: center;
    margin-bottom: 20px; /* Add space below the message */
    font-size: 18px; /* Adjust font size as needed */
    font-weight: bold; /* Make the message stand out */
}

/* Style for the recommended products section */
.recommended-products-section {
    display: flex;
    justify-content: space-between; /* Distribute items evenly */
    align-items: center;
    gap: 15px;
    padding: 0 15px; /* Add some padding to the sides */
}

/* Style for individual recommended product items */
.recommended-product-item {
    text-align: center;
    border-radius: 5px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    border: 2px solid #000; /* Assuming var(--black) is #000 */
    position: relative;
    padding: 20px;
    background-color: #fff; /* Assuming var(--white) is #fff */
    width: 350px; /* Increased width for larger cards */
    margin: 0 auto; /* Center the boxes */
}

.recommended-product-item img {
    height: 200px;
    width: 100%; /* Full width of the container */
    object-fit: cover;
    border-radius: 5px;
}

.recommended-product-item p {
    font-size: 18px;
    color: #333;
    padding: 5px 0;
}




/* Ensure full-width layout */
.container {
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
}





    </style>


        <!-- Payment Form -->
        <form action="process_payment.php" method="post" class="payment-section">
            <label for="card_number">Credit Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>
            <input type="submit" value="Pay Now" class="pay-btn">
            <a href="purchase.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>

