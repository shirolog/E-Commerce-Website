<?php 
require('./connect.php');

session_start();
session_unset();
session_destroy();

setcookie('search_box', '', time() - 1, '/');


header('Location: ../../user_login.php');
exit();


?>

