<?php
session_start();
session_destroy();
echo "Logged out success";
header("Location: home.html");
exit;
?>
