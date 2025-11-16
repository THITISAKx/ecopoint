<?php
include 'nav.php';
$error = "";

include 'config.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    $log = $conn->prepare("SELECT * FROM member WHERE mb_username = ? AND mb_password = ?");
    $log->bind_param("ss", $user, $pass);
    $log->execute();
    $result = $log->get_result();
    $data = $result->fetch_assoc();

    if($data > 0){
        $_SESSION['UID']=$data['mb_uid'];
        $_SESSION['firstname']=$data['mb_firstname'];
        $_SESSION['lastname']=$data['mb_lastname'];
        $_SESSION['role']=$data['mb_role'];
        $_SESSION['Point']=$data['total_point'];
        $_SESSION['username']=$data['mb_username'];
        header("Location:index.php");
        exit();
    }else{
        $error = "เกิดข้อผิดพลาดกรุณาลองใหม่อีกครั้ง";
    }
}

?>

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
                <h3 class="fw-bold">เข้าสู่ระบบ</h3>
            </div>
            <div class="card-body p-4">
                <form action="login.php" method="POST">
                    <div class="mb-2">
                        <label class="form-lable">username</label>
                        <input type="text" class="form-control" name="user" placeholder="username" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">password</label>
                        <input type="password" class="form-control" name="pass" placeholder="password" required>
                    </div>
                    <!-- check error -->
                    <?php if($error != ""){ ?>
                        <p class="form-label text-danger"><?php echo $error; ?></p>
                        <?php } ?>
                    <button class="btn btn-primary btn-sm my-2 w-100" type="submit">เข้าสู่ระบบ</button>
                </form>
                <a href="register.php" class="text-center">สมัครสมาชิก</a>
            </div>
        </div>
    </div>
</body>
</html>


