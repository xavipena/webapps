<?php
/*
 */
session_start();
$runner_id ="";
$page = "diet";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$newLang = $clean['language'];

//--- Query ------------------------ 

$_SESSION['lang'] = $newlang;
header("location: xDietUser_1.php");
?>
