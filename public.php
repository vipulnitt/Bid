<?php
include 'partials/_dbconnect.php';
$_SESSION['check']=0;
date_default_timezone_set("Asia/Calcutta"); 
$current_dt= date("Y-m-d")." ".date("H:i:s");
$_SESSION['term']="";
if(isset($_POST['sort']))
{
    if($_POST['SortBy']=='PName')
    $sql = "SELECT * FROM `uploads` ORDER BY `productname` ASC";
    if($_POST['SortBy']=='time')
    $sql = "SELECT * FROM `uploads` ORDER BY `id` DESC";
    if($_POST['SortBy']=='HBid')
    $sql = "SELECT * FROM `uploads` ORDER BY `highestbid` DESC";
}
else{
    $sql = "SELECT * FROM `uploads` ORDER BY `highestbid` DESC";
}
if(isset($_POST['search']))
{
    $_SESSION['term']=$_POST['str'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public</title>
    <link href="stylepublic.css" type="Text/Css" rel="Stylesheet">
</head>
<body>
<div id="search">
<form method="post" >
  <input type="text" placeholder="Search.." name="str">
  <button type="submit" name="search">Search</button>
</form>
<form method="post">
<select name="SortBy">
<option value="HBid">Highest Bid</option>
<option value="PName">Product Name</option>
<option value="time">Latest</option>
</select>
<input type="submit" name="sort" value="Sort" />
</form>
</div>
<table>
<?php
$res = mysqli_query($conn,$sql);
$xy=0;
if(mysqli_num_rows($res))
{    
    while($image=mysqli_fetch_assoc($res))
    {        
            $id_arr[$xy]=$image['id'];
            $pname =$image['productname'];
            $des=$image['description'];
            if(str_contains($pname, $_SESSION['term'])||str_contains($des, $_SESSION['term']))
         {
     ?>
<td rowspan='12'><image src="<?php echo $image['images'] ?>" height="300" width="300"></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b>Product Name:</b><?php echo $image['productname']; ?></td>
<tr><td>&nbsp;&nbsp;&nbsp; <b>Description:</b><?php echo $image['description']; ?></td></tr>
<form method="post">
<tr><td>&nbsp;&nbsp;&nbsp; <b>Posted BY:</b><button type="submit" class="user" name="postedby<?php echo $image['id']; ?>"><?php
echo $image['username'];
?></button></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b> Min BID:</b>Rs<?php echo $image['minimumbid']; ?></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b> BID till:</b>
<?php 
$dt=$image['time'];
if($current_dt<$dt)
echo $image['time'];
else
echo "Closed";
?></td></tr> 
<tr><td>&nbsp;&nbsp;&nbsp; <b> Higest BID:</b><?php 
$sqls = "SELECT * FROM `bidding` WHERE `bidding`.`productid` ='$id_arr[$xy]' ORDER BY `bidded` DESC";
$ress = mysqli_query($conn,$sqls);
$avg =0;
if(mysqli_num_rows($ress))
{
    $resall = mysqli_query($conn,$sqls); 
    while($r =mysqli_fetch_assoc($resall))
    {
      $avg+=$r['Rating'];
    }
    $avg = $avg/mysqli_num_rows($ress);
    $mbid = mysqli_fetch_assoc($ress);
    $maxbid=$mbid['bidded'];
    $maxbider=$mbid['username'];
    echo $mbid['bidded']." BY ";
    $sqlup = "UPDATE `uploads` SET `highestbid` = '$maxbid' WHERE `uploads`.`id` = '$id_arr[$xy]'";
    mysqli_query($conn,$sqlup);
    $sqlup = "UPDATE `uploads` SET `highestbider` = '$maxbider' WHERE `uploads`.`id` = '$id_arr[$xy]'";
    mysqli_query($conn,$sqlup);
} ?>
<button type="submit" class ="user" name="biduser<?php echo $image['id']; ?>"><?php
echo $image['highestbider'];
?></button>
</td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b> Rating:</b>
<select name="rating<?php echo $image['id']; ?>">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
</select>
<input type="submit" name="rate<?php echo $image['id']; ?>" value="rate" />
</td></tr> 
<tr><td>&nbsp;&nbsp;&nbsp; <b> Rate of Product:</b><?php echo $avg;?></td></tr>  
<tr><td>&nbsp;&nbsp;&nbsp; <b> Amount:</b>(Rs)<input type="number" name="bid_am"></td></tr>  
<tr><td>&nbsp;&nbsp;&nbsp;<button type="submit" name="bidnow<?php echo $image['id']; ?>">BID NOW</button></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;<button type="submit" name="comment<?php  echo $image['id']; ?>">COMMENTS</button></td></tr>
</form>
<tr><td id="brk" colspan="2">&nbsp;&nbsp;&nbsp;</td></tr>
     <?php
      $xy++;
}
}
if($xy==0)
{
    echo "<br><br><b>No Data Found</b>";
}
}
?>
</table>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{ 
    $current_dt= date("Y-m-d")." ".date("H:i:s");
    for($i=0;$i<sizeof($id_arr);$i++)
    {
    $temp='bidnow'.$id_arr[$i];
if(isset($_POST[$temp]))
{
    $bid_am = $_POST['bid_am'];
    $sqls = "SELECT * FROM `uploads` WHERE `uploads`.`id` =$id_arr[$i]";
    $res = mysqli_query($conn,$sqls);
    $fetch=mysqli_fetch_assoc($res);
    $by =$fetch['username'];
    $rsql = "SELECT * FROM `bidding` WHERE `bidding`.`username` ='$usrname'";
   $resu = mysqli_query($conn,$rsql);
    $numExistRows = mysqli_num_rows($resu);
    if($current_dt<$fetch['time'])
    {
        if($numExistRows==0)
    $sql ="INSERT INTO `bidding` (`username`, `productid`, `bidded`, `postedby`,`rating`) VALUES ('$usrname', '$id_arr[$i]', '$bid_am', '$by','0')";
         else
    $sql = "UPDATE `bidding` SET `bidded` = '$bid_am' WHERE `bidding`.`username` = '$usrname'";
   $result= mysqli_query($conn,$sql);
    }
   $_SESSION['check']=1;
}
$temp='comment'.$id_arr[$i];
if(isset($_POST[$temp]))
{
    include 'temp.php';
    $_SESSION['pid']=$id_arr[$i];
    echo '<script type="text/javascript">';
    echo 'window.location.href="comment.php";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=comment.php" />';
    echo '</noscript>'; 
}
$temp='biduser'.$id_arr[$i];
if(isset($_POST[$temp]))
{
   $sqls = "SELECT * FROM `uploads` WHERE `uploads`.`id` ='$id_arr[$i]'";
   $data = mysqli_query($conn,$sqls);
   $un = mysqli_fetch_assoc($data);
   if($un['highestbid'])
   {
    $_SESSION['user']=$un['highestbider'];
    echo '<script type="text/javascript">';
    echo 'window.location.href="profile.php";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=profile.php" />';
    echo '</noscript>'; 
   }
}
$temp='postedby'.$id_arr[$i];
if(isset($_POST[$temp]))
{
   $sqls = "SELECT * FROM `uploads` WHERE `uploads`.`id` ='$id_arr[$i]'";
   $data = mysqli_query($conn,$sqls);
   $un = mysqli_fetch_assoc($data);
    $_SESSION['user']=$un['username'];
    echo '<script type="text/javascript">';
    echo 'window.location.href="profile.php";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=profile.php" />';
    echo '</noscript>'; 
}
$temp='rate'.$id_arr[$i];
if(isset($_POST[$temp]))
{
    $sqls = "SELECT * FROM `uploads` WHERE `uploads`.`id` =$id_arr[$i]";
    $res = mysqli_query($conn,$sqls);
    $fetch=mysqli_fetch_assoc($res);
    $by =$fetch['username'];
    $rsql = "SELECT * FROM `bidding` WHERE `bidding`.`username` ='$usrname' AND `bidding`.`productid`='$id_arr[$i]' ";
    $resu = mysqli_query($conn,$rsql);
    $cx='rating'.$id_arr[$i];
    $rate = $_POST[$cx];
    $numExistRows = mysqli_num_rows($resu);
    if($numExistRows==0)
    $sql ="INSERT INTO `bidding` (`username`, `productid`, `bidded`, `postedby`,`Rating`) VALUES ('$usrname', '$id_arr[$i]', '0', '$by','$rate')";
    else
    $sql = "UPDATE `bidding` SET `Rating` = '$rate' WHERE `bidding`.`productid` = '$id_arr[$i]' AND `bidding`.`username` ='$usrname'";
    mysqli_query($conn,$sql);
 
}
}
}
if($_SESSION['check']==1)
{
    $_SESSION['check']=0;
  
}
?>