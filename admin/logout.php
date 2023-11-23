<?php 
//7.4 - logout with PHP session
session_start();    
$_SESSION = array();
session_destroy();
header('Location: login.php');
exit;
?>
