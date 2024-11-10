<?php
/*
Update user weight
*/
session_start();
include '../includes/dbConnect.inc.php';
include "../includes/app.security.inc.php";

$mea = $clean['mea'];
$prd = $clean['prd'];

$user = empty($_SESSION['diet_user']) ? 0 : $_SESSION['diet_user'];

$sql = "delete from diet_user_selection where IDuser = $user and IDmeal = $mea and IDproduct = $prd";
mysqli_query($db, $sql);
Header("Location: meals.php");
