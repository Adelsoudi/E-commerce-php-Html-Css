<!DOCTYPE html>
<html lang="en">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jersey+15&family=Platypi:ital,wght@0,300..800;1,300..800&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit products</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php
     include('config.php');
     $ID = $_GET['id'];
     $up = sqlsrv_query($conn , "select * from product where id=$ID");
     $data = sqlsrv_fetch_array($up);
    ?>
    <center>
         <div class="main">
            <form action="up.php" method="post" enctype="multipart/form-data">
                <h2>update the products</h2>
                <h2>Enter product id</h2><input type="text" name='id' value='<?php echo $data['id']?>'>
                <br>
               <h2>Enter product name</h2><input type="text" name='name'value='<?php echo $data['name']?>'>
                <br>
                <h2>Enter product price</h2><input type="text" name='price'value='<?php echo $data['price']?>'>
                <br>
                <input type="file" id="file" name='image' style='display:none;'>
                <label for="file">update product image</label>
                <button name='update' type='submit'>edit product</button>
                <br><br>
                <a href="products.php">show all products</a>
                </form>

         </div>
         <p>Developed By ADEL SOUDII</p>
    </center>
</body>
</html>