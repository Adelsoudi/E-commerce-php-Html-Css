<?php
include('config.php');

$ID=$_GET['id'];
sqlsrv_query($conn,"DELETE FROM purchase WHERE id=$ID");
header('location:card.php');

?>