<?php
include 'logout.php';
include 'partials/_dbconnect.php';
$pid=$_SESSION['pid'];
$sqls = "SELECT * FROM `bidding` WHERE `bidding`.`productid` ='$pid' ORDER BY `bidded` DESC";
$ress = mysqli_query($conn,$sqls);
if(mysqli_num_rows($ress))
{
    while($mbid = mysqli_fetch_assoc($ress))
    {
        echo "Rs.".$mbid['bidded']." BY ";
        echo $mbid['username']."<br>";
    }
}
?>