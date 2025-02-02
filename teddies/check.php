<?php 
session_start();
$_SESSION['runner_id'] = "moma";
$_SESSION['allowed']   = "yes";
header("location: cover.php");
?>
