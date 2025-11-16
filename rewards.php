<?php 
include 'nav.php';
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
    <title>Rewards</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<?php
include 'config.php';

// ดึงคะแนนของผู้ใช้
$sm1 = $conn->prepare("SELECT * FROM member WHERE mb_uid = ?");
$sm1->bind_param("s", $UID);
$sm1->execute();
$ttp = $sm1->get_result()->fetch_assoc();
?>

<h4 class="text-label text-warning">
    คะแนนของคุณ : <?php echo $ttp['total_point']; ?> 🪙
</h4>

<div class="container justify-content-center align-items-center mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-light text-center">
            <h3 class="fw-bold">แลกของรางวัล</h3>
            <?php if($_SESSION['role']=="admin"){?>
                <a href="insert_rewards.php" class="btn btn-outline-light">add menu</a>
            <?php } ?>    
        </div>

        <div class="card-body">
            <div class="row">

            <?php
            $res1 = $conn->query("SELECT * FROM tb_rewards");
            while ($rw = $res1->fetch_assoc()) {
            ?>
                <div class="col-4">
                    <div class="card h-100">
                        <img src="upload/<?php echo $rw['rw_image']; ?>" class="d-block p-img rounded-top-img">

                        <div class="card-body">
                            <span class="fw-bold"><?php echo $rw['rw_name']; ?></span>

                            <p>สินค้าคงเหลือ : <?php echo $rw['rw_stock']; ?></p>
                            <span class="fw-bold">ราคา : <?php echo $rw['rw_point']; ?> 🪙</span><br>

                            <?php if ($rw['rw_stock'] > 0) { ?>
                                <a href="?idrw=<?php echo $rw['rw_id']; ?>&point=<?php echo $rw['rw_point']; ?>" 
                                class="btn btn-primary btn-sm mt-1">แลกของรางวัล</a>
                            <?php } else { ?>
                                <button class="btn btn-danger btn-sm">หมด</button>
                            <?php } ?>

                            <?php if ($_SESSION['role'] == "admin") { ?>
                                <form action="" method="get" class="mt-2">
                                    <input type="hidden" name="add" value="<?php echo $rw['rw_id']; ?>">
                                    <input type="number" class="form-control" name="total" placeholder="จำนวนที่เพิ่ม">
                                    <input type="submit" class="btn btn-warning btn-sm mt-1" value="เพิ่มสต็อก">
                                </form>
                                    <a href="?derw=<?php echo $rw['rw_id'];?>" class="btn btn-danger btn-sm mt-1" onclick="return confirm('confirm')">ลบรายการ</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="container mt-5">
    <h2 class="text-primary fw-bold">🎖️ ตารางแลกของรางวัล 🎖️</h2>

    <table class="table table-striped table-hover align-middle">
        <tr>
            <th>วันที่</th>
            <th>รายการที่แลก</th>
            <th>ใช้คะแนน</th>
            <th>สถานะ</th>
        </tr>

        <?php 
        $sereq = $conn->prepare("
            SELECT tb_request.req_id, tb_request.req_date, tb_request.req_point, tb_request.req_status,
                   tb_rewards.rw_name
            FROM tb_request
            INNER JOIN tb_rewards ON tb_request.rw_id = tb_rewards.rw_id
            WHERE tb_request.mb_uid = ?
        ");
        $sereq->bind_param("s", $UID);
        $sereq->execute();
        $res2 = $sereq->get_result();

        if ($res2->num_rows > 0) {
            while ($data = $res2->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $data['req_date']; ?></td>
                    <td><?php echo $data['rw_name']; ?></td>
                    <td><?php echo $data['req_point']; ?></td>
                    <td><?php echo $data['req_status']; ?></td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='4' class='text-danger text-center'>❌ ไม่มีข้อมูลการแลก ❌</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

<?php
/* ===========================
   ระบบแลกของรางวัล
=========================== */

if (!empty($_GET['idrw'])) {
    $idrw  = $_GET['idrw'];
    $point = $_GET['point'];

    // ดึงคะแนนปัจจุบัน
    $stmt7 = $conn->prepare("SELECT total_point FROM member WHERE mb_uid = ?");
    $stmt7->bind_param("s", $UID);
    $stmt7->execute();
    $po = $stmt7->get_result()->fetch_assoc();

    if ($po['total_point'] < $point) {
        echo "<script>alert('คะแนนไม่เพียงพอ');location='rewards.php';</script>";
        exit();
    }

    // เพิ่มข้อมูลการแลก
    $stmt1 = $conn->prepare("INSERT INTO tb_request (mb_uid, rw_id, req_point) VALUES (?, ?, ?)");
    $stmt1->bind_param("sss", $UID, $idrw, $point);
    $stmt1->execute();

    // หักคะแนน
    $stmt8 = $conn->prepare("UPDATE member SET total_point = total_point - ? WHERE mb_uid = ?");
    $stmt8->bind_param("ss", $point, $UID);
    $stmt8->execute();

    // ลดสต็อกสินค้า
    $stmt2 = $conn->prepare("UPDATE tb_rewards SET rw_stock = rw_stock - 1 WHERE rw_id = ?");
    $stmt2->bind_param("s", $idrw);
    $stmt2->execute();

    echo "<script>alert('แลกสำเร็จ!');location='rewards.php';</script>";
    exit();
}

/* ===========================
   เพิ่มสต็อก (Admin)
=========================== */

if (!empty($_GET['add'])) {
    $addid = $_GET['add'];
    $total = $_GET['total'];

    $add = $conn->prepare("UPDATE tb_rewards SET rw_stock = rw_stock + ? WHERE rw_id = ?");
    $add->bind_param("ss", $total, $addid);
    $add->execute();

    echo "<script>alert('เพิ่มสต็อกแล้ว');location='rewards.php';</script>";
    exit();
}

/* ===========================
   ลบรายการสินค้า (Admin)
=========================== */

if(!empty($_GET['derw'])){
    $idrw = $_GET['derw'];

    $delete = $conn->prepare("DELETE FROM tb_rewards WHERE rw_id = ?");
    $delete->bind_param("s", $idrw);
    if($delete->execute()){
        echo "<script>window:location='rewards.php';</script>";
        exit();
    }
}
?>
