<?php
include 'nav.php';
include 'config.php';
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
    <div class="card shadow-sm">
        <div class="card-header bg-secondary d-flex justify-content-between align-items-center flex-warp">
            <form action="member.php" method="POST">
                <div class="d-flex gap-2">
                <input type="search" class="form-control" name="search">
                <button class="btn btn-light" type="submit">🔍</button>
                <button class="btn btn-light" type="submit">🔄️</button>
                </div>
        
            </form>
                <button class="btn btn-warning"  data-bs-toggle="modal" data-bs-target="#addmadal">
                    เพิ่มสมาชิก
                </button>
        </div> 
    </div>
    <table class="table table-striped table-hover align-middle ">
        <thead>
        <tr class="table-success">
            <th>id</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>ชื่อผู้ใช้</th>
            <th>คะแนน</th>
            <th>สถานะ</th>
            <th colspan="2">แก้ไข & ลบ</th>
        </tr>
</thead>
<?php
if (isset($_POST['search'])){
    $sex = "%{$_POST['search']}%";
    $stmt1 = $conn->prepare("SELECT * FROM member WHERE mb_uid LIKE ? 
    OR mb_firstname LIKE ?
    OR mb_lastname LIKE ?
    OR mb_username LIKE ? 
    OR mb_role LIKE ?");
    $stmt1->bind_param("sssss", $sex,$sex,$sex,$sex,$sex,);
    $stmt1->execute();
    
    $result = $stmt1->get_result();
} else{
    $result = $conn->query("SELECT * FROM member");
} 
 while($data = mysqli_fetch_array($result)){ ?>
<tbody>
    <tr>
        <td> <?php echo $data['mb_uid'];?></td>
        <td> <?php echo $data['mb_firstname'];?></td>
        <td> <?php echo $data['mb_lastname'];?></td>
        <td> <?php echo $data['mb_username'];?></td>
        <td> <?php echo $data['total_point'];?></td>
        <td> <?php echo $data['mb_role'];?></td>
        <td><a href="update.php?uid=<?php echo $data['mb_uid'];?>">✂️</a></td>
        <td><a href="?delete=<?php echo $data['mb_uid'];?>" onclick="return confirm('confirm')">🗑️</a></td>
    </tr>
     <?php } ?>
 </body>
 </table>
 
 </body>
</html>

 <?php
 if (isset($_GET['delete'])){
    $uid = $_GET['delete'];
    $delete = "DELETE FROM member WHERE mb_uid = '$uid'";
    if($conn->query($delete)){
        echo"<script>window:location='member.php';</script>";
        exit();
    } else {
        echo "<script> alert ('ลบข้อมูลผิดพลาด');</script>";
    }
 }
 ?>

 <?php
if (isset($_POST['add_member'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    $stmt = prepare("INSERT INTO member WHERE mb_firstname , mb_lastname, mb_username, mb_password, mb_role
    VALUES '?,?,?,?,?,'");
    $stmt->bind_param("sssss", $firstname,$lastname,$username,$pass,$role);
    if($stmt->execute()){
    echo "<script> alert ('เพิ่มข้อมูลสำเร็จ'); window:location='member.php';</script>";
    }
} 
?>