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
    <title>POll</title>
    <link href="style.css" type="Text/Css" rel="Stylesheet"> 
</head>
<body>

<form method="post">
<h1>POLL BOOTH</h1>
<button type="submit" class ="lgn" name="login">Login</button>
<button type="submit" class="lgn" name="signup">Signup</button>
</form>
</body>
</html>
<?php
      
      if(isset($_POST['login'])) {
        header("Location:login.php");
      }
      if(isset($_POST['signup'])) {
        header("Location:signup.php");
      }
  ?>
    