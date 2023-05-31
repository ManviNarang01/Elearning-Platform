<?php
 session_start();
 session_destroy();
 setcookie('rememberMe', '', time() - 3600, '/');
 unset($_COOKIE['rememberMe']);
 echo "<script> location.href='index.php'; </script>";
?>