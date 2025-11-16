<?php
$servername = "localhost";
$username = "root";
$password = "";
$dB_name = "db_eco2";

$conn = new mysqli($servername,$username,$password,$dB_name);

if($conn->connect_error){
    die("Error : Connect" . $conn->connect_error);
}
