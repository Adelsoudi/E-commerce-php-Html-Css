<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mycart</title>
    <style>
        
           table {
            width: 40%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            text-decoration: none;
        }

        
       
    </style>
</head>
<body>
    <center>
       <h3>
          your cart
      </h3>
    </center>
    <?php
    include('config.php');
    $result = sqlsrv_query($conn, "SELECT * FROM purchase");
    while ($row = sqlsrv_fetch_array($result)) {
        echo "
        <center>
            <main>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>Product Name</th>
                            <th scope='col'>Product Price</th>
                            <th scope='col'>Delete Product</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>$row[name]</td>
                            <td>$row[price]</td>
                            <td><a href='del_card.php?id=$row[id]' <button class='delete-button'>Delete</button></a></td>
                        </tr>
                    </tbody>
                </table>
            </main>
        </center>
        ";
    }
    ?>

    <center>

    <a href="shop.php">Return to product page</a>
    </center>
</body>
</html>