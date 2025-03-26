<?php
session_start();
session_destroy();
echo "Logged out success";
header("Location: index.php");
exit;
?>
