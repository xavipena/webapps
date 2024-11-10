<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

if (!isset($_SESSION['diet_user'])) {

    Header("location: xDietMix.php?cat=".$clean['cat']);
}

// --------------------------------
// Delete table
// --------------------------------

$sql = "delete from diet_user_mix where IDuser = ".$_SESSION['diet_user'];
mysqli_query($db, $sql);

Header("location: xDietMix.php?cat=".$clean['cat']);
