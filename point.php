<?php 
include 'nav.php';
include 'config.php';
$UID = $_SESSION['UID'];

if(!isset($_SESSION['username'])){
    echo "<script>alert('กรุณาเข้าสู่ระบบ');window:location='index.php';</script>";
    exit();
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
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-warp">
            <form action="point.php" method="GET">
                <div class="d-flex gap-2">
                    <select name="select" class="form-select form-select-sm">
                        <option value="">--แสดงผลทั้งหมด--</option>
                        <option value="รอการอนุมัติ">รออนุมัติ</option>
                        <option value="อนุมัติแล้ว">อนุมัติแล้ว</option>
                    </select>
                    <button class="btn btn-success">แสดง</button>
                </div>
            </form>
                <button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#addmadal">
                    เพิ่มข้อมุล
                </button>
        </div> 
    </div>
    <!-- select point -->
    <?php
        $sm1 = $conn->prepare("SELECT * FROM member WHERE mb_uid = ?");
        $sm1->bind_param("s" ,$UID);
        $sm1->execute();
        $res2 = $sm1->get_result();
        $ttp=$res2->fetch_assoc();
    ?>
    <h4>คะแนนของคุณ : <?php echo $ttp['total_point'];?></h4>

    <!-- table -->
    <table class="table table-striped table-hover align-middle ">
        <tr class="table-success">
            <th>ID Point</th>
            <th>หัวข้อ</th>
            <th>สถานะ</th>
            <th>ลบ</th>
        </tr>
        <?php

        if(!empty($_GET['select'])){
            $select = $_GET['select'];
            $sp = $conn->prepare(" SELECT tb_point.p_id , tb_point.p_topic,
            member.mb_uid, member.mb_firstname, tb_point.p_status 
            FROM tb_point
            INNER JOIN member ON tb_point.mb_uid = member.mb_uid
            WHERE tb_point.p_status = ? AND tb_point.mb_uid = ?
            ORDER BY tb_point.p_date DESC");
            $sp->bind_param("ss", $select, $UID);
        }else{
            $sp = $conn->prepare(" SELECT tb_point.p_id , tb_point.p_topic,
            member.mb_uid, member.mb_firstname, tb_point.p_status 
            FROM tb_point
            INNER JOIN member ON tb_point.mb_uid = member.mb_uid
            WHERE tb_point.mb_uid = ?
            ORDER BY tb_point.p_date DESC");
            $sp->bind_param("s" ,$UID);
        }

        $sp->execute();
        $res1 = $sp->get_result();
        if($res1->num_rows > 0){
        while($data = $res1->fetch_assoc()){?>
        <tr>
            <td><?php echo $data['p_id'];?></td>
            <td><a href="smp.php?idp=<?php echo $data['p_id'];?>" class="btn btn-success"><?php echo $data['p_topic'];?></a></td>
            <td><?php echo $data['p_status'];?></td>
            <td><a href="?delete=<?php echo $data['p_id'];?>" class="btn btn-danger" onclick="return confirm('ยืนยันการลบข้อมูล')">🗑️</a></td>
        </tr>
        <?php } ?>
        <?php }else{ ?>
            <td colspan="4" align="center"><p class="text-danger">❌ไม่มีข้อมูล❌</p></td>
       <?php } ?>
    </table>
    <div class="modal fade" id="addmadal" tabindex="-1" aria-labelledby="Lableformodal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light" >
                    <span class="modal-title" id="Lableformodal">แลกคะแนน</span>

                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-2">
                            <label class="form-lable">หัวข้อ</label>
                            <input type="text" class="form-control" name="topic">
                        </div>
                        <div class="mb-2">
                            <label class="form-lable">รูปภาพ</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                        <div class="modal-footer">
                            <a href="point.php" class="btn btn-success btn-sm">ปิด</a>
                            <button class="btn btn-warning" type="submit">บันทึกข้อมุล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if(!empty($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = $conn->prepare("DELETE FROM tb_point WHERE p_id = ?");
    $delete->bind_param("s", $id);
    if($delete->execute()){
        echo "<script>window:location='point.php';</script>";
    }
}
?>

<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $dir = "point/";
    $tp = $_POST['topic'];

    $image = basename($_FILES['image']['name']);
    $move = $dir.$image;

    $ISP = $conn->prepare("INSERT INTO tb_point (mb_uid, p_topic, p_image) VALUES(?,?,?)");
    $ISP->bind_param("sss", $UID, $tp, $image);
    if($ISP->execute()){
        move_uploaded_file($_FILES['image']['tmp_name'],$move);
        echo "<script>alert('บันทึกข้อมูล');window:location='point.php';</script>";
    }

}
?>

<?php
//approve
// if(!empty($_GET['total'])){
    
//     $total = $_GET['total'];
//     $uid = $_GET['uid'];
//     $pid = $_GET['pid'];

//     $stmt1 = $conn->prepare("UPDATE tb_point SET p_status = ? , p_total = ? WHERE p_id = ?");
//     $app = "อนุมัติแล้ว";
//     $stmt1->bind_param("sss", $app, $total, $pid);
//     $stmt1->execute();

//     $stmt2 = $conn->prepare("UPDATE member SET total_point = total_point + ? WHERE mb_uid = ?");
//     $stmt2->bind_param("ss", $total, $uid);
//     if($stmt2->execute()){
//         echo "<script>alert('บันทึกข้อมูล');window:location='point.php';</script>";
//     }

// }
?>