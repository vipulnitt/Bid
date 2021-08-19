<?php
include 'logout.php';
include 'partials/_dbconnect.php';
include 'temp.php';
$uname=$_SESSION['user'];
$sql = "SELECT * FROM `users` WHERE `username` ='$uname'";
$fe = mysqli_query($conn,$sql);
$data=mysqli_fetch_assoc($fe);
$email=$data['Email'];
$mno =$data['mno'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
   <b>UserName:<?php echo $uname; ?><br><br>
    Email:<?php echo $email; ?><br><br>
    Mobile Number:<?php echo $mno; ?></b>
</body>
</html>