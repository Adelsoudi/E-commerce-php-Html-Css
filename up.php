<?php

include('config.php');

if(isset($_POST['update'])){

    $ID = $_POST['id'];
    $NAME  = $_POST['name'];
    $PRICE = $_POST['price'];
    $IMAGE = $_FILES['image'];
    $image_location = $_FILES['image']['tmp_name'];
    $image_name     = $_FILES['image']['name'];
    $image_up       = "images/".$image_name;
    $update = "UPDATE product SET name='$NAME' , price = '$PRICE' , image =  '$image_up' WHERE id = $ID";
    sqlsrv_query($conn , $update);
    if(move_uploaded_file($image_location, 'images/'.$image_name)){
        echo "<script>alert('The product successfully uploaded')</script>";
    }else{
        echo "<script>alert('error in uploading please try again')</script>";
    }
    header('location: index.php');
}

?>