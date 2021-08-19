<?php
include 'logout.php';
include 'partials/_dbconnect.php';
date_default_timezone_set("Asia/Calcutta"); 
$current_dt= date("Y-m-d")." ".date("h:i:s");
if(isset($_POST['sub'])&&isset($_FILES['img_upload']))
{   
    $description=$_POST['desciption'];
    $pname =$_POST['pname'];
    $mbid =$_POST['mbid'];
    $time =$_POST['datetime'];
    $img_name= $_FILES['img_upload']['name'];
    $img_size= $_FILES['img_upload']['size'];
    $tmp_name= $_FILES['img_upload']['tmp_name'];
    $error = $_FILES['img_upload']['error'];
    if($error==0)
    {
        if($img_size<206747)
        {
            $img_ex = pathinfo($img_name,PATHINFO_EXTENSION);
            $img_ex_l = strtolower($img_ex);
            $allowed = array("jpg","jpeg","png");
            if(in_array($img_ex_l,$allowed))
            {    
                 $img_new_name = uniqid("IMG-",true).'.'.$img_ex_l;
                 $img_path ='uploads/'.$img_new_name;
                 move_uploaded_file($tmp_name,$img_path);
                 $sql ="INSERT INTO `uploads` (`username`, `productname`, `description`, `minimumbid`,`time`, `images`) VALUES ('$usrname', '$pname', '$description', '$mbid','$time', '$img_path')";
                 $result = mysqli_query($conn,$sql);
                 if($result)
                 echo "Successful!";
                 else
                 echo "fail";
            }
            else
            {
                echo "Invalid Format!";
            }
        }
        else
        {
            echo "Size limit is 200kb";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Product</title>
    <link href="style_welcome.css" type="Text/Css" rel="Stylesheet">
</head>
<body>
<div class="container">
<form method="post" enctype="multipart/form-data">
Product Name:<input type="text" placeholder="Enter Product Name" name="pname" >
Description:<input type="text" placeholder="Enter Desiption" name="desciption" >
Minimum BID Amount (Rs):<input type="number" placeholder="Minimum BID AMOUNT" name="mbid">
Close:<input type="datetime-local" name="datetime">
<input type="file" name="img_upload">
<button type="submit" id="lg" name="sub">ADD</button>
</form>
</div>
<table>
<?php
$sql = "SELECT * FROM `uploads` ORDER BY `id` ASC";
$res = mysqli_query($conn,$sql);
$x=0;
if(mysqli_num_rows($res))
{    
    while($image=mysqli_fetch_assoc($res))
    {    
        if($image['username']==$usrname){
            $id_arr[$x]=$image['id'];
            $x++;
     ?>
<td rowspan='9'><image src="<?php echo $image['images'] ?>" height="300" width="300"></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b>Product Name:</b><?php echo $image['productname'] ?></td>
<tr><td>&nbsp;&nbsp;&nbsp; <b>Description:</b><?php echo $image['description'] ?></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b>Closing Time:</b><?php echo $image['time'] ?></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp; <b> Min BID:</b>Rs<?php echo $image['minimumbid'] ?></td></tr>
<form method="post">
<tr><td>&nbsp;&nbsp;&nbsp;<button type="submit" name="delete<?php echo $image['id']; ?>">Delete</button></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;<button type="submit" name="edit<?php echo $image['id']; ?>">Edit</button></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;<button type="submit" name="bid<?php echo $image['id']; ?>">BIDS</button></td></tr>
<tr><td>&nbsp;&nbsp;&nbsp;<button type="submit" name="comment<?php echo $image['id']; ?>">COMMENTS</button></td></tr>
        </form>
     <?php
    }
}
}
?>
</table>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    for($i=0;$i<$x;$i++)
    {
        $temp='delete'.$id_arr[$i];
        if(isset($_POST[$temp]))
        {
            $sql = "SELECT * FROM `uploads` WHERE `uploads`.`id`=$id_arr[$i]";
            $res = mysqli_query($conn,$sql);
            $image=mysqli_fetch_assoc($res);
            $status=unlink($image['images']);    
            if($status){  
            echo "File deleted successfully";    
            }else{  
            echo "Sorry!";    
             }  
            $sql ="DELETE FROM `uploads` WHERE `uploads`.`id` = $id_arr[$i]";
            mysqli_query($conn,$sql);
            header("Refresh:0");
        }
        $temp='edit'.$id_arr[$i];
        if(isset($_POST[$temp]))
        {
            $sql = "SELECT * FROM `uploads` WHERE `uploads`.`id`=$id_arr[$i]";
            $res = mysqli_query($conn,$sql);
            $image=mysqli_fetch_assoc($res);
            $_SESSION['productname']=$image['productname'];
            $_SESSION['description']=$image['description'];
            $_SESSION['images']=$image['images'];
            $_SESSION['minimumbid']=$image['minimumbid'];
            $_SESSION['time']=$image['time'];
            $_SESSION['id']=$id_arr[$i];
            header('Location:edit.php');
        }
        $temp='comment'.$id_arr[$i];
if(isset($_POST[$temp]))
{
    $_SESSION['pid']=$id_arr[$i];
    header('Location:comment.php');
}
$temp='bid'.$id_arr[$i];
if(isset($_POST[$temp])){
    $_SESSION['pid']=$id_arr[$i];
    header("Location:bids.php"); 
}
          
    }
}
?>