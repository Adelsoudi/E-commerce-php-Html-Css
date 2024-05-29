<!DOCTYPE html>
<html lang="en">
<head>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Jersey+15&family=Platypi:ital,wght@0,300..800;1,300..800&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>
    <style>
        h3{
            font-family: "Playfair Display", serif;
            font-weight: bold; 
        }
        .card{
            float: left;
            margin-top: 40px;
            margin-left: 10px;
            margin-right: 10px;
        }
        .card img{
            width: 100%;
            height: 200px;
        }
        main{
            width: 60%;
        }
        .navbar-brand{
            margin-left: 70px;
            text-decoration: none;
            color: white;

        }
    </style>
</head>
<body>
    <nav class = "navbar navbar dark bg-dark">
        <a class = "navbar-brand" href="card.php">My card</a>


    </nav>
    <center>
     <h3>Available products</h3>
    </center>
    <?php
    include('config.php');
    $result = sqlsrv_query($conn,"SELECT * FROM product");
    while ($row = sqlsrv_fetch_array($result)) {
        echo "
        <center>
        <main>
        <div class='card' style='width: 18rem;'>
          <img src='$row[image]' class='card-img-top'>
          <div class='card-body'>
             <h5 class='card-title'>$row[name]</h5>
             <p class='card-text'>$row[price]</p>
             
             <a href='val.php? id=$row[id]'class='btn btn-success'>add to cart</a>
          </div>
      </div>
        </main>
        <center>
        ";
    }
    
    
    
    ?>



</body>
</html>