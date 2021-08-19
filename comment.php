<?php
include 'logout.php';
include 'partials/_dbconnect.php' ;
include 'temp.php';
$_SESSION['check']=0;
$username= $_SESSION['username'];
$pid= $ab;
$id_arr[0]=0;
if(isset($_POST['postc']))
{
    $comment=$_POST['com'];
    if($_SESSION['edit']==0)
    {
    $sql="INSERT INTO `comments` (`username`, `comment`, `productid`) VALUES ('$username', '$comment', '$pid')";
    mysqli_query($conn,$sql);
    }
    else if($_SESSION['edit']==1)
    {
        $str=$_SESSION['updateid'];
        $sql="UPDATE `comments` SET `comment` = '$comment' WHERE `comments`.`id` = '$str'";
        $result = mysqli_query($conn,$sql);
        $_SESSION['edit']=0;
        $_SESSION['value']="";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>
</head>
<body>
<form method="post">
<textarea  name="com" rows="4" cols="50" placeholder="Write your comments here.."><?php
if($_SESSION['edit']==1)
{
 echo $_SESSION['value'];
 $_SESSION['check']=0;
}
 ?></textarea><br>
<input type="submit" name="postc" value="POST"><br>
<?php
$sqls = "SELECT * FROM `comments` WHERE `productid`='$pid' ORDER BY `id` DESC";
$res = mysqli_query($conn,$sqls);
if(mysqli_num_rows($res))
{    $xy=0;
    while($c=mysqli_fetch_assoc($res))
    { 
        $id_arr[$xy]=$c['id'];
         ?>
         <br>
         <b><?php echo $c['username'];?>:</b>
         <?php echo $c['comment'];?>
         <?php 
         if($c['username']==$username)
         {
             ?>
             <input type="submit" name="edit<?php echo $c['id'];?>" value="EDIT">
             <input type="submit" name="delete<?php echo $c['id'];?>" value="DELETE"><br>
             <?php
         }
         ?>
         <?php
         $xy++;
    }
}
?>
</form>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{ 
    for($i=0;$i<sizeof($id_arr);$i++)
    {
    $temp='edit'.$id_arr[$i];
if(isset($_POST[$temp]))
{
    $sql = "SELECT * FROM `comments` WHERE `comments`.`id`='$id_arr[$i]'";
    $res = mysqli_query($conn,$sql);
    $d=mysqli_fetch_assoc($res);
    $_SESSION['value']=$d['comment'];
    $_SESSION['updateid']=$id_arr[$i];
    $_SESSION['edit']=1;
    $_SESSION['check']=1;
}
$temp='delete'.$id_arr[$i];
if(isset($_POST[$temp]))
{
    $sqls = "DELETE FROM `comments` WHERE `comments`.`id`='$id_arr[$i]'";
    mysqli_query($conn,$sqls);
    $_SESSION['edit']=0;
    $_SESSION['check']=1;
}
}
}
if($_SESSION['check']==1)
{
    header('Location:comment.php');
    $_SESSION['check']=0;
}
?>