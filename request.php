<?php 
include 'nav.php';
include 'config.php';
$UID = $_SESSION['UID'];
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
    
    <div class="container justify-content-center align-items-center mt-5"> 
    <div class="card shadow-sm border-0">
        <div class="row">
            <div class="col-12">
                <div class="card-header bg-primary text-light text-center">
                    <h3 class="fw-bold">แลกของรางวัล</h3>
                </div>
            </div>
        </div>
        <form action="request.php" method="GET">
            <div class="d-flex gap-2">
                <select name="select" class="form-select form-select-sm">
                    <option value="">--แสดงผลทั้งหมด--</option>
                    <option value="รอการอนุมัติ">รออนุมัติ</option>
                    <option value="อนุมัติแล้ว">อนุมัติแล้ว</option>
                </select>
                <button class="btn btn-success">แสดง</button>
            </div>
            </form>
     <table class="table table-striped table-hover align-middle ">
            <th>วันที่</th>
            <th>ชื่อผู้ใช้</th>
            <th>รายการที่แลก</th>
            <th>ใช้คะแนน</th>
            <th>สถานะ</th>
            <!-- <th>ยกเลิก</th> -->

        </tr>
        <?php
        if(!empty($_GET['select'])){
            $se = $_GET['select']; 
        $sereq = $conn->prepare("SELECT 
        tb_request.req_id, 
        tb_request.req_date, 
        tb_request.req_point, 
        tb_request.req_status,
        tb_rewards.rw_id,
        tb_rewards.rw_name,
        member.mb_uid,
        member.mb_firstname
        FROM tb_request
        INNER JOIN tb_rewards ON tb_request.rw_id = tb_rewards.rw_id
        INNER JOIN member ON tb_request.mb_uid = member.mb_uid
        WHERE tb_request.req_status = ?");
        $sereq->bind_param("s", $se);
        }else{
        $sereq = $conn->prepare("SELECT 
        tb_request.req_id, 
        tb_request.req_date, 
        tb_request.req_point, 
        tb_request.req_status,
        tb_rewards.rw_id,
        tb_rewards.rw_name,
        member.mb_uid,
        member.mb_firstname
        FROM tb_request
        INNER JOIN tb_rewards ON tb_request.rw_id = tb_rewards.rw_id
        INNER JOIN member ON tb_request.mb_uid = member.mb_uid");
        }
        $sereq->execute();
        $res2 = $sereq->get_result();
        if($res2->num_rows > 0){
            while($data = $res2->fetch_assoc()){
        ?>
        <tr>
            <td><?php echo $data['req_date'];?></td>
            <td><?php echo $data['mb_firstname'];?></td>
            <td><?php echo $data['rw_name'];?></td>
            <td><?php echo $data['req_point'];?></td>
            <?php if($data['req_status'] == "รอการอนุมัติ"){?>
                <td><a href="?approve=<?php echo $data['req_id'];?>" class="btn btn-success">✅อนุมัติ</a></td>
            <?php }else{
                echo "<td><p class='text-lable text-success'>✅อนุมัติแล้ว</a></td>";
            } ?>
        </tr>
        <?php } ?>
        <?php }else{
            echo "<td colspan='4' align='center' class='text-danger'>❌ไม่ข้อมูลการแลก❌</td>";
        } ?>
    </table>
</body>
</html>



<?php
if(!empty($_GET['approve'])){
    $idrq = $_GET['approve'];
    
    $stmt = $conn->prepare("UPDATE tb_request SET req_status = ? WHERE req_id = ?");
    $app = "อนุมัติแล้ว";
    $stmt->bind_param("ss" ,$app, $idrq);
    if($stmt->execute()){
        echo "<script>alert('บันทึกข้อมูล');window:location='request.php';</script>";
        exit();
    }
}
?>




<?php
// if(!empty($_GET['idrw'])){
//     $idrw = $_GET['idrw'];
//     $point = $_GET['point'];

//     $stmt7 = $conn->prepare("SELECT * FROM member WHERE mb_uid = ?");
//     $stmt7->bind_param("s" , $UID);
//     $stmt7->execute();
//     $res3=$stmt7->get_result();
//     $po = $res3->fetch_assoc();

//     if($po['total_point'] < $point){
//         echo "<script>alert('คะแนนไม่เพียงพอ');window:location='rewards.php';</script>";
//         exit();
//     }

//     $stmt1 = $conn->prepare("INSERT INTO tb_request (mb_uid, rw_id, req_point) VALUES(?,?,?)");
//     $stmt1->bind_param("sss", $UID, $idrw, $point);
//     $stmt1->execute();

//     $stmt8 = $conn->prepare("UPDATE member SET total_point = total_point - ? WHERE mb_uid = ?");
//     $stmt8->bind_param("ss", $point, $UID);
//     $stmt8->execute();
    
//     $stmt2 = $conn->prepare("UPDATE tb_rewards SET rw_stock = rw_stock - ? WHERE rw_id = ?");
//     $dis = 1;
//     $stmt2->bind_param("ss",$dis, $idrw);
//     if($stmt2->execute()){
//         echo "<script>alert('บันทึกข้อมูล');window:location='rewards.php';</script>";
//         exit();
//     }
// }

// if(!empty($_GET['delete'])){
//     $de = $_GET['delete'];
//     $stmt4 = $conn->prepare("DELETE FROM tb_request WHERE req_id = ?");
//     $stmt4->bind_param("s", $de);
//     $stmt4->execute();

//     $stmt6 = 
// }


?>