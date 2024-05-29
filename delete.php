<?php

include('config.php');
$ID = $_GET['id'];
sqlsrv_query($conn, "DELETE FROM product WHERE id=$ID");
header('location: products.php')

?>