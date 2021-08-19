<!DOCTYPE html>
<?php
if($_SERVER["REQUEST_METHOD"]=="POST")
{
include 'partials\_dbconnect.php';
$username = $_POST["uname"];
$password = $_POST["psw"];
$password_repeat = $_POST["psw-repeat"];
$email = $_POST['email'];
$mno=$_POST['mno'];
$exists=false;
$rsql = "SELECT * FROM `users` WHERE username ='$username'";
$result = mysqli_query($conn,$rsql);
$numExistRows = mysqli_num_rows($result);
if($numExistRows>0)
{
  $exists=true;
  echo "Username Already Exist!";
}
if($password==$password_repeat&&$exists==false)
{
  $hash= password_hash($password,PASSWORD_DEFAULT);
  $sql ="INSERT INTO `users` ( `username`,`mno`,`Email`, `password`, `dt`) VALUES ('$username','$mno','$email' ,'$hash', current_timestamp())";
   $result = mysqli_query($conn,$sql);
   if($result)
   {
    header("Location:login.php");
   }
}
else
{
  if($exists==false)
  echo "Password Not Match!";
}
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="style.css" type="Text/Css" rel="Stylesheet"> 
</head>
<body>
<form style="border:1px solid #ccc" method="post" action="signup.php">
  <div class="container">
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
    
    <label for="uname"><b>UserName</b></label>
    <input type="text" placeholder="Enter UserName" name="uname" required>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="mno"><b>Mobile Number</b></label>
    <input type="text" placeholder="Enter Mobile number" name="mno" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" required>


    <div class="clearfix">
      <button type="submit" class="signupbtn" name="signup">Sign Up</button>
    </div>
  </div>
</form>
</body>
</html>
