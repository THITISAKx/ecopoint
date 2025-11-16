<?php
include 'nav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <?php
    include 'config.php';
    $idp = $_GET['idp'];

    $sql="SELECT * FROM tb_point WHERE p_id = '$idp'";
    $result = $conn->query($sql);
    $data = mysqli_fetch_array($result);
    ?>
<body>
    <a href="point.php" class="btn btn-success">🏠กลับ</a>
    <img src="point/<?php echo $data['p_image'];?>">
</html>