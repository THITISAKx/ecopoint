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
            <div class="card-header bg-info text-light text-center">
                <h3 class="fw-bold">เพิ่มรายการสินค้า</h3>
            </div>
            <div class="card-body p-4">
                <form action="insert_rewards.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-2">
                        <label class="form-lable">ชื่อรายการสินค้า</label>
                        <input type="text" class="form-control" name="name" placeholder="ชื่อรายการสินค้า" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">ราคาสินค้า</label>
                        <input type="text" class="form-control" name="price" placeholder="ชื่อรายการสินค้า" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">จำนวนสินค้า</label>
                        <input type="text" class="form-control" name="stock" placeholder="ชื่อรายการสินค้า" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-lable">รูปภาพ</label>
                        <input type="file" class="form-control" name="image">
                    </div>
                    <div class="mb-2">
                        <input type="submit" class="btn btn-info btn-sm form-control" value="add">
                    </div>
                </form>
                <a href="rewards.php" class="text-center">กลับ🏠</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include 'config.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    $dir = "upload/";
    $image = basename($_FILES['image']['name']);
    $move = $dir. $image;

    $stmt = $conn->prepare("INSERT INTO tb_rewards(rw_name, rw_image, rw_point, rw_stock) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss", $name, $image, $price, $stock);
    if($stmt->execute()){
        move_uploaded_file($_FILES['image']['tmp_name'], $move);
        echo "<script>alert('success');window:location='rewards.php';</script>";
        exit();
    }
}


