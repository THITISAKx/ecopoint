<?php  session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <header class="sticky-top shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark bg-success">
            <div class="container">
                <a href="index.php" class="navbar-brand d-flex align-items-center">
                    <img src="web/11.png" class="me-2 rounded-circle border border-0" width="36" height="36">
                    <span class="fw-bold ms-1">Ecopoint</span>
                </a>
            <div class="collasea navbar-collasea" id="mianNav">
                <ul class="navbar-nav me-auto mb-2 mb-0-lg">
                    <li class="nav-item"> <a href="index.php" class="nav-link active">หน้าแรก</a></li>
                    <li class="nav-item"> <a href="point.php" class="nav-link">แลกคะแนน</a></li>
                    <li class="nav-item"> <a href="rewards.php" class="nav-link">แลกของรางวัล</a></li>
                    <?php
                    if(isset($_SESSION['username']) && $_SESSION['role'] == "admin"){
                    echo "<li class='nav-item'><a href='mn_point.php' class='nav-link'>point</a></li>";
                    echo "<li class='nav-item'><a href='member.php' class='nav-link'>member</a></li>";
                    echo "<li class='nav-item'><a href='request.php' class='nav-link'>Request</a></li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
            if(isset($_SESSION['username'])) { ?>
                <div class='d-flex gap-2'>
                    <p class='text-lable text-light'><?php echo $_SESSION['firstname']. " " . $_SESSION['lastname'];?></p>
                    <a href='logout.php' class='btn btn-danger btn-sm'>Logout</a>
                </div>
            <?php } else{
                echo "<div class='d-flex gap-2'>";
                echo "<a href='login.php' class='btn btn-outline-light btn-sm'>เข้าสู่ระบบ</a>";
                echo "<a href='register.php' class='btn btn-warning btn-sm'>สมัครสมาชิก</a>";
                echo "</div>";
            }?>
            </div>
        </nav>
    </header>
</body>
</html>