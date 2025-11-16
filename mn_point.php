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
        <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-warp">
            <form action="" method="GET">
                <div class="d-flex gap-2">
                    <select name="select" class="form-select form-select-sm">
                        <option value="">แสดงผลทั้งหมด</option>
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
    <table class="table table-striped table-hover align-middle ">
        <tr class="table-success">
            <th>id</th>
            <th>ชื่อ</th>
            <th>หัวข้อ</th>
            <th>สถานะ</th>
            <th>อัพเดท สถานะ</th>
            <th>ลบ</th>
        </tr>
        <?php
        if(!empty($_GET['select'])){
            $select = $_GET['select'];
            $sp = $conn->prepare(" SELECT tb_point.p_id , tb_point.p_topic,
            member.mb_uid, member.mb_firstname, tb_point.p_status 
            FROM tb_point
            INNER JOIN member ON tb_point.mb_uid = member.mb_uid
            WHERE tb_point.p_status = ?
            ORDER BY tb_point.p_date DESC");
            $sp->bind_param("s", $select);
        }else{
            $sp = $conn->prepare(" SELECT tb_point.p_id , tb_point.p_topic,
            member.mb_uid, member.mb_firstname, tb_point.p_status 
            FROM tb_point
            INNER JOIN member ON tb_point.mb_uid = member.mb_uid
            ORDER BY tb_point.p_date DESC");
        }

        $sp->execute();
        $res1 = $sp->get_result();
        while($data = $res1->fetch_assoc()){?>
        <tr>
            <td><?php echo $data['p_id'];?></td>
            <td><?php echo $data['mb_firstname'];?></td>
            <td><?php echo $data['p_topic'];?></td>
            <td><?php echo $data['p_status'];?></td>

            <?php if($data['p_status'] == "รอการอนุมัติ"){
                echo "<td>";
                echo "<form action='?approve' method='GET'>";
                echo "<div class='d-flex gap-2'>";
                echo "<input type='hidden' name='pid' value='{$data['p_id']}'>";
                echo "<input type='hidden' name='uid' value='{$data['mb_uid']}'>";
                echo "<select name='total' class='form-select form-select-sm'>";
                echo "<option value='5'>5 point</option>";
                echo "<option value='10'>10 point</option>";
                echo "<option value='15'>15 point</option>";
                echo "</select>";
                echo "<button class='btn btn-success'>ส่ง</button>";
                echo "</div>";
                echo "</form>";
                echo "</td>";
            }else{
                echo "<td class='text-lable text-success'>✅อนุมัติแล้ว</td>";
            }
            ?>
            <td><a href="?delete=<?php echo $data['p_id'];?>" class="btn btn-danger" onclick="return confirm('ยืนยันการลบข้อมูล')">ลบ🗑️</a></td>
        </tr>
        <?php } ?>
    </table>
    <div class="modal fade" id="addmadal" aria-labelledby="Lableformodal" aria-hidden="true">
        <div class="modal-dielog modal-dielog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <span class="modal-title">แลกคะแนน</span>
                </div>
                <div class="modal-body">
                    <form action="point.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-2">
                            <label class="form-lable"></label>
                            <input type="text" cless="form-control" name="">
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

//approve
if(!empty($_GET['total'])){
    
    $total = $_GET['total'];
    $uid = $_GET['uid'];
    $pid = $_GET['pid'];

    $stmt1 = $conn->prepare("UPDATE tb_point SET p_status = ? , p_total = ? WHERE p_id = ?");
    $app = "อนุมัติแล้ว";
    $stmt1->bind_param("sss", $app, $total, $pid);
    $stmt1->execute();

    $stmt2 = $conn->prepare("UPDATE member SET total_point = total_point + ? WHERE mb_uid = ?");
    $stmt2->bind_param("ss", $total, $uid);
    if($stmt2->execute()){
        echo "<script>alert('บันทึกข้อมูล');window:location='mn_point.php';</script>";
    }

}