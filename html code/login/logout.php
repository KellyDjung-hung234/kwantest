<?php
session_start();

// 清除 cookies
setcookie('C_username', '', time() - 3600);

// 清除 session
session_unset();
session_destroy();

// 重定向到登入頁面
header("Location: login.php");
exit();
?>