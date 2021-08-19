<?php
session_start();
if($_SESSION["loggedin"]!=true||!isset($_SESSION["loggedin"]))
{
    header("Location:login.php");
    exit;
}
$usrname=$_SESSION["username"];
if(isset($_POST['logout'])) {
    header("Location:login.php");
    session_destroy();
  }
  if(isset($_POST['home'])) {
    header("Location:welcome.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserPage</title>
    <link href="style_welcome.css" type="Text/Css" rel="Stylesheet">
</head>
<body>
    <form method="post">
    <div class="container">
<div class="lgs">
    <button type="submit" class="log" name="home">HOME</button>
    <button type="submit" class="log" name="logout">LOGOUT</button>
</div>
<div class="username">
<?php echo "UserName: ",$usrname; ?>
</div>
</div>
</form>
</body>
</html>