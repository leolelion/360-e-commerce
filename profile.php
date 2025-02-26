<?php
session_start();
require_once 'config.php';
$sql = "SELECT * FROM users WHERE user_id = 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
//do something w/ resluts 
mysqli_free_result($result);
mysqli_close($conn);
?>