<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include './includes/dbConnect.inc.php';

// --------------------------------
// Delete table
// --------------------------------

$sql =  "delete from diet_user_salad";
mysqli_query($db, $sql);

Header("location: xDietAmanides.php");
