<?php 
//7.4 log out which also involve PHP session.
session_start();    
$_SESSION = array();
session_destroy();
header('Location: login.php');
exit;
?>
