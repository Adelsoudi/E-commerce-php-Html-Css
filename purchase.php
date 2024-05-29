<?php

include 'connect.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = sqlsrv_query($con, "SELECT * FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(sqlsrv_num_rows($select_cart) > 0){
      $message[] = 'The item succsessfully added to cart';
   }else{
      sqlsrv_query($con, "INSERT INTO cart(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'The item succsessfully added to cart';
   }

};

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   sqlsrv_query($con, "UPDATE cart SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'shoping cart updated';
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   sqlsrv_query($con, "DELETE FROM cart WHERE id = '$remove_id'") or die('query failed');
   header('location:purchase.php');
}
  
if(isset($_GET['delete_all'])){
   sqlsrv_query($con, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');
   header('location:purchase.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shoping cart</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="purchase.css">      
   

</head>
<body>
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">

<div class="user-profile">

<?php
$select_user = sqlsrv_query($con, "SELECT * FROM users WHERE id = '$user_id'") or die('Query failed');
if ($fetch_user = sqlsrv_fetch_array($select_user, SQLSRV_FETCH_ASSOC)) {
    // User data fetched successfully
}
?>

<p>Current user: <span><?php echo isset($fetch_user['name']) ? $fetch_user['name'] : ''; ?></span></p>
   <div class="flex">
      <a href="purchase.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are you sure from signing out?');" class="delete-btn">sign out</a>
   </div>

</div>

<div class="products">

   <h1 class="heading">Products</h1>

   <div class="box-container">

   <?php
   include('connect.php');
   $result = sqlsrv_query($con, "SELECT * FROM product");      
   while($row = sqlsrv_fetch_array($result)){
   ?>
      <form method="post" class="box" action="">
         <img src="admin/<?php echo $row['image']; ?>"  width="200">
         <div class="name"><?php echo $row['name']; ?></div>
         <div class="price"><?php echo $row['price']; ?></div>
         <input type="number" min="1" name="product_quantity" value="1">
         <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
   <?php
      };
   ?>

   </div>

</div>

<div class="shopping-cart">

   <h1 class="heading">Shoping cart</h1>

   <table>
    <thead>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total price</th>
        <th>Delete</th>
    </thead>
    <tbody>
    <?php
$grand_total = 0;
$cart_query = sqlsrv_query($con, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
if ($cart_query !== false) {
    while ($fetch_cart = sqlsrv_fetch_array($cart_query, SQLSRV_FETCH_ASSOC)) {
        ?>
        <tr>
            <td><img src="admin/<?php echo $fetch_cart['image']; ?>" height="75" alt=""></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td><?php echo $fetch_cart['price']; ?> </td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                    <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                    <input type="submit" name="update_cart" value="Update" class="option-btn">
                </form>
            </td>
            <td><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></td>
            <td><a href="purchase.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Remove from shopping cart');">Delete</a></td>
        </tr>
        <?php
        $grand_total += $sub_total;
    }
} else {
    echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">Cart is empty</td></tr>';
}
?>
<tr class="table-bottom">
    <td colspan="4">Total price:</td>
    <td><?php echo $grand_total; ?></td>
    <td><a href="purchase.php?delete_all" onclick="return confirm('Delete all products');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Delete all</a></td>
</tr>
<!-- Inside your shopping cart table -->
<tr>
    <td colspan="6">
        <a href="recommendation.php" class="checkout-btn">Proceed to Checkout</a>
    </td>
</tr>

    </tbody>
</table>




</div>

</div>

</body>
</html>