<!DOCTYPE html>
<html lang="en">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jersey+15&family=Platypi:ital,wght@0,300..800;1,300..800&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce|add products</title>
    <link rel="stylesheet" href="index.css">
  
</head>
<body>
    <center>
         <div class="main">
            <form action="insert.php" method="post" enctype="multipart/form-data">
                <h2> Online Shope</h2>
                <img src="ecommerce-1.jpg" alt="logo" width="450px">
               <h2>Enter product name</h2><input type="text" name='name'>
                <br>
                <h2>Enter product price</h2><input type="text" name='price'>
                <br>
                <input type="file" id="file" name='image' style='display:none;'>
                <label for="file">choose image</label>
                <button name='upload'>upload product</button>
                <br><br>
                <a href="products.php">show all products</a>
                </form>

         </div>
         <p>Developed By ADEL SOUDII</p>
    </center>
</body>
</html>