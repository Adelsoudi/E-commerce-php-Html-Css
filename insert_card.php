<?php

include('config.php');
if(isset($_POST['add'])){
    $NAME = $_POST['name'];
    $PRICE = $_POST['price'];
    $insert = "INSERT INTO purchase(name,price) VALUES('$NAME','$PRICE')";
    sqlsrv_query($conn,$insert);
    header('location: card.php');

}
?>