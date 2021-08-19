<?php
include 'logout.php';
include 'partials/_dbconnect.php';
$id=$_SESSION['id'];
$productname=$_SESSION['productname'];
$description=$_SESSION['description'];
$images=$_SESSION['images'];
$minimumbid=$_SESSION['minimumbid'];
$time=$_SESSION['time'];
$result=false;
if(isset($_POST['update']))
{
    $u_pname=$_POST['pname'];
    $u_des=$_POST['des'];
    $u_time=$_POST['datetime'];
    $u_bid=$_POST['bid'];
    if(isset($_FILES['img_upload']))
    {
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
                 $sql="UPDATE `uploads` SET `images` = '$img_path' WHERE `uploads`.`id` = $id";
                 echo $sql;
                 $result = mysqli_query($conn,$sql);
                 if($result)
                 $status=unlink($images); 
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
    if($u_pname!=$productname)
    {
        $sql="UPDATE `uploads` SET `productname` = '$u_pname' WHERE `uploads`.`id` = $id";
                 $result = mysqli_query($conn,$sql);
    }
    if($u_des!=$description)
    {
        $sql="UPDATE `uploads` SET `description` = '$u_des' WHERE `uploads`.`id` = $id";
                 $result = mysqli_query($conn,$sql);
    }
    if($u_bid!=$minimumbid)
    {
        $sql="UPDATE `uploads` SET `minimumbid` = '$u_bid' WHERE `uploads`.`id` = $id";
                 $result = mysqli_query($conn,$sql);
    }
    $sql="UPDATE `uploads` SET `time` = '$u_time' WHERE `uploads`.`id` = $id";
                 $result = mysqli_query($conn,$sql);
    if($result)
    {
        header("Location:myproduct.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <form method="post" enctype="multipart/form-data">
        Product Name:<input type="text" name="pname" value="<?php echo $productname;?>">
        Description:<input type="text" name="des" value="<?php echo $description;?>">
        MIN BID:(Rs)<input type="text" name="bid" value="<?php echo $minimumbid;?>">
        Closing time:<input type="datetime-local" name="datetime" >
        <input type="file" name="img_upload">
        <button type="submit" name="update">Update</button><br>
        <img src="<?php echo $images;?>" height="400" width="400">
</form>
</head>
<body>
</body>
</html>