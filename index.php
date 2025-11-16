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
    <script src="js/bootstrap.min.js"></script>

    <style>
        .hero {
            height: 80vh;
            object-fit: cover;
            width: 100%;
        }
        .p-img{
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- ================= Carousel ================== -->
<div class="carousel slide my-2" id="heroCarousel" data-bs-ride="slide">
    <div class="carousel-indicators carousel-indicators-sm">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="งานนำเสนอ3/สไลด์4.PNG" class="d-block hero">
        </div>
        <div class="carousel-item">
            <img src="งานนำเสนอ3/สไลด์5.PNG" class="d-block hero">
        </div>
        <div class="carousel-item">
            <img src="งานนำเสนอ3/สไลด์6.PNG" class="d-block hero">
        </div>
    </div>
</div>

<!-- ================= ข่าวสาร ================== -->
<div class="container justify-content-center align-items-center">
    <div class="card shadow-sm border-0">

        <div class="card-header bg-success text-light d-flex justify-content-between align-items-center">
            <h3 class="fw-bold m-0">ข่าวสาร</h3>

            <!-- ปุ่มเฉพาะ Admin -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") { ?>
                <button class="btn btn-light btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                    + เพิ่มข่าว
                </button>
            <?php } ?>
        </div>

        <div class="card-body">
            <div class="row">

                <?php
                $sl1 = "SELECT * FROM tb_news ORDER BY n_id DESC";
                $res1 = $conn->query($sl1);
                while($news = mysqli_fetch_array($res1)){ ?>
                
                <div class="col-4 my-2">
                    <div class="card h-100">
                        <img src="upload/<?php echo $news['n_image'];?>" class="d-block p-img rounded-top-img">
                        <div class="card-body">
                            <span class="fw-bold"><?php echo $news['n_topic'];?></span>
                            <p><?php echo $news['n_detail'];?></p>
                            <button class="btn btn-primary btn-sm">อ่านเพิ่มเติม</button>
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"){?>
                                <a href="?delete=<?php echo $news['n_id'];?>" class="btn btn-danger btn-sm mx-2" onclick="return confirm('confirm')">🗑️delete</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<!-- ================= แลกของรางวัล ================== -->
<div class="container justify-content-center align-items-center mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-light text-center">
            <h3 class="fw-bold mt-2">แลกของรางวัล</h3>
        </div>

        <div class="card-body">
            <div class="row">

                <?php 
                $sl2 = "SELECT * FROM tb_rewards";
                $res2 = $conn->query($sl2);
                while($rw = mysqli_fetch_array($res2)){
                ?>
                <div class="col-4 my-3">
                    <div class="card h-100">
                        <img src="upload/<?php echo $rw['rw_image'];?>" class="d-block p-img rounded-top-img">
                        <div class="card-body">
                            <span class="fw-bold"><?php echo $rw['rw_name'];?></span>
                            <p>จำนวน Point : <?php echo $rw['rw_point'];?></p>
                            <a href="rewards.php" class="btn btn-primary btn-sm">แลกของรางวัล</a>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<!-- ================= Footer ================== -->
<footer class="bg-success">
    <div class="bg-dark text-light">
        <div class="p-5">
            <h6 class="fw-bold ms-5">🗨️ ช่องทางการติดต่อ</h6>
            <small>
                <p class="ms-5">📞 : 065-7144-757 <br><br>
                🌐 : mvc.ac.th <br><br>
                🏠 : วิทยาลัยอาชีวศึกษามหาสารคาม</p>
            </small>
        </div>
    </div>
</footer>

<!-- ================= Modal Add News ================== -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="" method="POST" enctype="multipart/form-data">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">เพิ่มข่าวใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="fw-bold">หัวข้อข่าว</label>
                    <input type="text" name="topic" class="form-control" required>

                    <label class="fw-bold mt-3">รายละเอียด</label>
                    <textarea name="detail" class="form-control" rows="4" required></textarea>

                    <label class="fw-bold mt-3">รูปภาพ</label>
                    <input type="file" name="image" class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">บันทึกข่าว</button>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>

<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $nt = $_POST['topic'];
    $nd = $_POST['detail'];
    $dir = "upload/";
    $nim = basename($_FILES['image']['name']);
    $move = $dir.$nim;
    
    $stmt = $conn->prepare("INSERT INTO tb_news(n_topic, n_detail, n_image)VALUE(?,?,?)");
    $stmt->bind_param("sss", $nt, $nd, $nim);
    if($stmt->execute()){
        move_uploaded_file($_FILES['image']['tmp_name'],$move);
        echo "<script>alert('success');window:location='index.php';</script>";
        exit();
    }
}

if(!empty($_GET['delete'])){
    $idn = $_GET['delete'];
    $stmt1 = $conn->prepare("DELETE FROM tb_news WHERE n_id = ?");
    $stmt1->bind_param("s", $idn);
    if($stmt1->execute()){
        echo "<script>window:location='index.php';</script>";
        exit();
    }
}