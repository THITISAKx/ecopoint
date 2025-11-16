<?php include 'nav.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 30rem;">
            <div class="card-header bg-success text-light text-center">
                <h3 class="fw-bold">สมัครสมาชิก</h3>
            </div>
            <div class="card-body p-4">
                <form action="register.php" method="POST">
                    <div class="mb-2">
                        <label class="form-lable">ชื่อ</label>
                        <input type="text" class="form-control" name="fname" placeholder="Firstname" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">นามสกุล</label>
                        <input type="text" class="form-control" name="lname" placeholder="Lastname" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">username</label>
                        <input type="text" class="form-control" name="user" placeholder="username" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">password</label>
                        <input type="password" class="form-control" name="pass" placeholder="password" required>
                    </div>
                     <div class="mb-2">
                        <label class="form-lable">ที่อยู่</label>
                        <textarea name="home"  class="form-control" rows="3" placeholder="ที่อยู่" require></textarea>
                    </div>
                    <div class="mb-2">
                        <input type="checkbox" class="form-clickbox my-2" required>
                        <label class="form-lable">ยืนยันการสมัครสมาชิก</label>
                    </div>
                    <button class="btn btn-warning btn-sm my-2 w-100" type="submit">สมัครสมาชิก</button>
                </form>
                <a href="login.php" class="text-center">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include 'config.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $home = $_POST['home'];

    $sql = $conn->prepare("INSERT INTO member(mb_firstname, mb_lastname, mb_username, mb_password, mb_address) VALUES(?,?,?,?,?)");
    $sql->bind_param("sssss", $fname, $lname, $user, $pass, $home);
    if($sql->execute()){
        echo "<script>alert('บันทึกข้อมูล');window:location='login.php';</script>";
        exit();
    }else{
        echo "<script>alert('Error');window:location='register.php';</script>";
        exit();
    }
}
?>