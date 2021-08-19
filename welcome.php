<?php
include 'logout.php';
if(isset($_POST['myproduct']))
{
    header("Location:myproduct.php");
}
if(isset($_POST['profile']))
{
    $_SESSION['user']=$_SESSION["username"];
    header("Location:profile.php");
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
&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" id="lg" name="myproduct">MY PRODUCT</button>
<button type="submit" id="lg" name="profile">MY PROFILE</button>
</form>
</body>
</html>
<?php
$_SESSION['edit']=0;
include 'public.php';
?>