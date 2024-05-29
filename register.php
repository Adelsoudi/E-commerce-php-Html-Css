<?php

include ('connect.php');

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    
    // Prepare the SQL statement with parameter placeholders
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $params = array($email, $pass);
    
    // Execute the prepared statement
    $select = sqlsrv_query($con, $sql, $params);
    if ($select === false) {
        die('Query failed: ' . print_r(sqlsrv_errors(), true));
    }
    
    if (sqlsrv_has_rows($select)) {
        $message[] = 'User already exists!';
    } else {
        // Prepare the SQL statement for insertion
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $params = array($name, $email, $pass);
      
        // Execute the prepared statement for insertion
        $insert = sqlsrv_query($con, $sql, $params);
        if ($insert === false) {
            die('Insertion failed: ' . print_r(sqlsrv_errors(), true));
        }
      
        $message[] = 'Registered successfully!';
        header('Location: login.php');
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

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
      <h3>Create new account</h3>
      <input type="text" name="name" required placeholder="User name" class="box">
      <input type="email" name="email" required placeholder="Email address" class="box">
      <input type="password" name="password" required placeholder="Password" class="box">
      <input type="password" name="cpassword" required placeholder="Confirm password" class="box">
      <input type="submit" name="submit" class="btn" value="Create account">
      <p>aready have account?<a href="login.php">sign in</a></p>
   </form>

</div>

</body>
</html>