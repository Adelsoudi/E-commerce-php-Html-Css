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
$connn = sqlsrv_connect($serverName, $connectionOptions);

if (!$connn) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetching data from the "cart" table
$sql = "SELECT user_id, name FROM cart";
$stmt = sqlsrv_query($connn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Preparing the dataset for the Apriori algorithm
$transactions = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $transactions[$row['user_id']][] = $row['name'];
}

// Transforming the dataset into the required format for the Apriori algorithm
$dataset = [];
foreach ($transactions as $transaction) {
    $dataset[] = array_values($transaction);
}

// Debug: Print the dataset to verify
echo "Dataset: ";
print_r($dataset);

// Include the Apriori library (ensure you have installed it via Composer)
require 'vendor/autoload.php';
use Phpml\Association\Apriori;

// Initialize the Apriori algorithm
$apriori = new Apriori($support = 0.5, $confidence = 0.3);

// Train the algorithm with the dataset
$apriori->train($dataset, []);

// Get the association rules
$rules = $apriori->getRules();

// Debug: Print the rules to verify
echo "Rules: ";
print_r($rules);

// Save the rules to the database
foreach ($rules as $rule) {
    $antecedent = implode(', ', $rule['antecedent']);
    $consequent = implode(', ', $rule['consequent']);
    $support = $rule['support'];
    $confidence = $rule['confidence'];

    $sqlInsert = "INSERT INTO apriori_rules (antecedent, consequent, support, confidence) VALUES (?, ?, ?, ?)";
    $params = [$antecedent, $consequent, $support, $confidence];
    $stmtInsert = sqlsrv_query($connn, $sqlInsert, $params);

    if ($stmtInsert === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Debug: Confirm the insert operation
    echo "Inserted rule: ($antecedent => $consequent) with support $support and confidence $confidence\n";
}

sqlsrv_close($connn);
?>
