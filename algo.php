<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Support Count</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #80BDE3;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #80E3BD;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Support </h1>
        <table>
            <tr>
                <th>Product</th>
                <th>Support</th>
            </tr>
            <?php
            // Database connection parameters
            $serverName = "DESKTOP-DKGIRAK";
            $database = "onlineshope";
            $uid = ""; // Replace with your username
            $pass = ""; // Replace with your password

            // Establishing the connection to the SQL Server
            $connectionOptions = [
                "Database" => $database,
                "Uid" => $uid,
                "PWD" => $pass
            ];
            $conn = sqlsrv_connect($serverName, $connectionOptions);

            if (!$conn) {
                die(print_r(sqlsrv_errors(), true));
            }

            // Fetching data from the "cart" table
            $sql = "SELECT user_id, name FROM cart";
            $stmt = sqlsrv_query($conn, $sql);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            // Preparing the dataset for the Apriori algorithm
            $transactions = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $transactions[$row['user_id']][] = $row['name'];
            }

            // Count how many times each product is found
            $productCounts = [];
            foreach ($transactions as $transaction) {
                foreach ($transaction as $product) {
                    if (!isset($productCounts[$product])) {
                        $productCounts[$product] = 0;
                    }
                    $productCounts[$product]++;
                }
            }

            // Display the count for each product
            foreach ($productCounts as $product => $count) {
                echo '<tr>';
                echo '<td>' . $product . '</td>';
                echo '<td>' . $count . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        <p>Total Transactions: <?php echo count($transactions); ?></p>
    </div>
</body>
</html>
