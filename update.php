<?php
include 'config.php';
include 'nav.php';
$uid = $_GET['uid'];
$sql = "SELECT * FROM member WHERE mb_uid='$uid'";
$result = $conn->query($sql);
$data = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 30rem;">
            <div class="card-header bg-success text-light text-center">
                <h3 class="fw-bold">แก้ไขข้อมูล</h3>
            </div>
            <div class="card-body p-4">
                <form action="update.php" method="POST">
                    <input type="hidden" value="<?php echo $uid;?>" name = "uid">
                    <div class="mb-2">
                        <label class="form-lable">ชื่อ</label>
                        <input type="text" class="form-control" name="fname" value="<?php echo $data['mb_firstname'];?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">นามสกุล</label>
                        <input type="text" class="form-control" name="lname" value="<?php echo $data['mb_lastname'];?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">username</label>
                        <input type="text" class="form-control" name="user" value="<?php echo $data['mb_username'];?>" required>
                    </div>
                    <button class="btn btn-warning btn-sm my-2 w-100" type="submit">แก้ไข</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php 

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $ID = $_POST['uid'];
    $fname =$_POST['fname'];
    $lname =$_POST['lname'];
    $username =$_POST['user'];
    
    $sql = "UPDATE member SET mb_firstname ='$fname', mb_lastname ='$lname', mb_username ='$username'
    WHERE mb_uid='$ID'";

    if($conn->query($sql));
    echo "<script>alert('อัพเดทข้อมูลสำเร็จ');window:location='member.php';</script>";
    exit();
}