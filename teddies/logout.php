<?php 
session_start();
$_SESSION['runner_id'] = "";
header("location: login.php");
?>