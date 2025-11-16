<?php

// register
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $home = $_POST['home'];

    $sql = $conn->prepare("INSERT INTO member(mb_firstname, mb_lastname, mb_username, mb_password, mb_address) VALUES(?,?,?,?,?)");
    $sql->bind_param("sssss", $fname, $lname, $user, $pass, $home);
    if($sql->execute()){
        echo "<script>alert('บันทึกข้อมูล')window:location='login.php';</script>";
        exit();
    }
}

//login
if($_SERVER['RRQUEST_METHOD']=="POST"){
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
        header("Location:index.php");
        exit();
    }
}

//point
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $dir = "point/";
    $tp = $_POST['topic'];
    
    $image = basename($_FILES['image']['name']);
    $move = $dir.$image;

    $ISP = $conn->prepare("INSERT INTO tb_point(mb_uid, p_topic, p_image,) VALUES(?,?,?)");
    $ISP->bind_param("sss", $uid, $tp, $image);
    if($ISP->execute()){
        move_uploaded_file($_FILES['image']['tmp_name'],$move);
        echo "<script>alert('บันทึกข้อมูล')window:location='point.php';</script>";
    }

}

// insert news 

if($_SERVER['REQUEST_METHOD']=="POST"){
    $topic = $_POST['topic'];
    $detail = $_POST['detail'];
    $dir = "news/";
    $image = basename($_FILES['image']['name']);
    $move = $dir.$image;

    $isn = $conn->prepare("INSERT INTO tb_news(n_topic, n_detail, n_image) VALUES(?,?,?)");
    $isn->bind_param("sss", $topic, $detail, $image);
    if($isn->execute()){
        move_uploaded_file($_FILES['image']['tmp_name'],$move);
        echo "<script>alert('บันทึกข้อมูล')window:location='point.php';</script>";
    }
}

//approve

if(!empty($_GET['approve'])){
    $idp = $_GET['']
}
