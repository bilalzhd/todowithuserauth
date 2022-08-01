<?php
  $inserted = false;
  $wpass = false;
  $usernameExists = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    require 'partials/_dbconnect.php';
    $email = $_POST['email'];
    $username = $_POST['uname'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $existSql = "SELECT * FROM `users3` WHERE `username` = '$username'";
    $exists = mysqli_query($conn, $existSql);
    $row = mysqli_num_rows($exists);
    if($row == 0){
      if($password == $cpassword){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users3` ( `username`, `email`, `password`, `dt`) VALUES ( '$username', '$email', '$hash', current_timestamp());";
        $result = mysqli_query($conn, $sql);
        if($result){
          $inserted = true;
        
        }
      } else {
        $wpass = true;
      }
    } else {
      $usernameExists = true;
    }

}

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body>
    <?php include 'partials/_nav.php'?>
    <?php 
    if($inserted){
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> Your account is created, you can now login with your credentials.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    if($wpass){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong> Passwords do not match.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    if($usernameExists){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong> Username already exists.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <div class="container col-md-6">
      <h3 class="my-4 display-4">Signup</h3>
    <form action="signup.php" method="post">
      <div class="mb-3">
        <label for="uname" class="form-label">Username</label>
        <input type="text" class="form-control" id="uname" name="uname" aria-describedby="emailHelp">
      </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" maxlength="20" id="password" name="password">
    <div id="passwordHelpBlock" class="form-text">
  Your password must be 8-20 characters long.
</div>
</div>
  <div class="mb-3">
    <label for="cpassword" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="cpassword" name="cpassword">
</div>
  <button type="submit" class="d-flex btn btn-primary">Submit</button>
</form>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>
