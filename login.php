<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
if($_SESSION['loggedin']==true)
{
  header('Location:welcome.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="style.css" type="Text/Css" rel="Stylesheet"> 
</head>
<body>
<form method="post">
  <div class="imgcontainer">
    <img src="avtar.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <button type="submit">Login</button>
  </div>
</form> 
</body>
</html>
<?php
if($_SERVER["REQUEST_METHOD"]=="POST")
{
include 'partials\_dbconnect.php';
$username = $_POST["uname"];
$password = $_POST["psw"];
$login=false;
$sql = "SELECT * FROM `users` WHERE username ='$username'";
$result = mysqli_query($conn,$sql);
$numExistRows = mysqli_num_rows($result);
$data=mysqli_fetch_assoc($result);
if($numExistRows==1&&password_verify($password,$data['password']))
{
  $login=true;
  session_start();
  $_SESSION['username']=$username;
  $_SESSION['loggedin']=true;
  header("Location:welcome.php");
}

}
?>