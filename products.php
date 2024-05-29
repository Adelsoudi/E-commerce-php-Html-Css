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
        h3 {
            font-family: "Playfair Display", serif;
            font-weight: bold;
            text-align: center; /* Center the heading */
        }
        .container {
           max-width: 3000px;
           margin: 250px auto;
           background: #fff;
           padding: 400px;
           border-radius: 8px;
           box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
           text-align: center; /* Center content */
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 15px; /* Add some padding to the sides */
        }

        .box {
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--black);
            position: relative;
            padding: 20px;
            background-color: var(--white);
            width: 350px; /* Increased width for larger cards */
        }

        .box img {
            height: 200px;
            width: 65%;
            object-fit: cover;
            border-radius: 5px;
        }

        .name {
            font-size: 15px;
            color: var(--black);
            padding: 5px 0;
        }

        .price {
            font-size: 18px;
            color: orange;
            padding-top: 5px;
        }

        .btn-group {
            display: flex;
            justify-content: space-between; /* Evenly distribute buttons */
            margin-top: 10px;
        }

        .btn-danger,
        .btn-primary {
            flex: 1; /* Equal width for both buttons */
            margin: 0 5px; /* Space between buttons */
        }
    </style>
</head>
<body>

<main class="container">
    <h3>All products</h3>
    <a href="index.php" class="btn btn-success mb-4">Add New Product</a>
    <div class="box-container">
        <?php
        include('config.php');
        $result = sqlsrv_query($conn,"SELECT * FROM product");
        while ($row = sqlsrv_fetch_array($result)) {
            echo "
            <div class='box'>
                <img src='$row[image]' alt='$row[name]'>
                <div class='name'>$row[name]</div>
                <div class='price'>$row[price]</div>
                <div class='btn-group'>
                    <a href='delete.php?id=$row[id]' class='btn btn-danger'>Delete product</a>
                    <a href='update.php?id=$row[id]' class='btn btn-primary'>Edit product</a>
                </div>
            </div>
            ";
        }
        ?>
    </div>
</main>
</body>
</html>

