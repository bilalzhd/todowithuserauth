<?php 
require 'partials/_dbconnect.php';
$error = false;
$login = false;

if($_SERVER["REQUEST_METHOD"]=="POST"){
  $username = $_POST['uname'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM `users3` WHERE `username` = '$username'";
  $result = mysqli_query($conn, $sql);
  $num = mysqli_num_rows($result);
  if($num==1){
    while ($row = mysqli_fetch_assoc($result)) {
      if(password_verify($password, $row['password'])){
        $login = true;
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['table'] = $username;
        header("location: home.php");
      } else {
        $error = true;

      }
      
    }
  } else {
    $error = true;
  }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body>
    <style>
      #signuplink {
        text-decoration: none;
        font-weight: bold;
        color: red;
      }
    </style>
    <?php require 'partials/_nav.php'; ?>
    <?php 
    if($error){
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong> Invalid credentials.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }

    if($login){
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong></strong>You are now logged in.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  }
    ?>

  <div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 mb-3">Login to your todos account</h1>
        <p class="col-lg-10 fs-4">You can login to your account by entering the username and the password you would have gotten after signing up. If you are a new member <a href="signup.php" id="signuplink">sign up</a> first and then you can login.</p>
      </div>
      <div class="col-md-10 mx-auto col-lg-5">
        <form class="p-4 p-md-5 border rounded-3 bg-light" action="login.php" method="post">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="uname" name="uname" placeholder="username">
            <label for="uname">Username</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
          </div>
          
          <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>
