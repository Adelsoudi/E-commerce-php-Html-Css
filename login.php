<?php

include ('connect.php');
session_start();

if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    
    // Prepare the SQL statement with parameter placeholders
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$pass'";
    $params = array($email, $pass);
    
    // Prepare and execute the statement
    $stmt = sqlsrv_prepare($con, $sql, $params);
    if ($stmt === false) {
        die('Statement preparation failed: ' . print_r(sqlsrv_errors(), true));
    }
    
    // Execute the statement
    $result = sqlsrv_execute($stmt);
    if ($result === false) {
        die('Statement execution failed: ' . print_r(sqlsrv_errors(), true));
    }
    
    // Fetch the result
    $rows = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $row;
    }
    
    $rowCount = count($rows);
    
    if ($rowCount > 0) {
        $user = $rows[0];
        $_SESSION['user_id'] = $user['id'];
        header('Location: purchase.php');
        exit;
    } else {
        $message[] = 'Incorrect password or email!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="purchase.css">
   <style>
      input{
         text-align: center;
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>Sign in</h3>
      <input type="email" name="email" required placeholder="Email" class="box">
      <input type="password" name="password" required placeholder="Password " class="box">
      <input type="submit" name="submit" class="btn" value="Log in">
      <p>Don't have an account?<a href="register.php"> sign up</a></p>
   </form>

</div>

</body>
</html>