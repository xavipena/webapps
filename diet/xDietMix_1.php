<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";

if (!isset($_SESSION['diet_user'])) {

    Header("location: xDietMix.php?cat=$cat");
}

include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$meal = 1;
if (!empty($_SESSION['meal'])) 
{
    $meal = $_SESSION['meal']; 
} 

$cat = empty($clean['cat']) ? 0 : $clean['cat'];
$mix = empty($clean['mix']) ? 0 : $clean['mix'];
    
//--- Query ------------------------ 

// if already exists, ignore
$sql =  "insert ignore into diet_user_mix set".
        " IDuser      = ".$_SESSION['diet_user'].
        ",IDcat       = ".$cat.
        ",IDmix       = ".$mix.
        ",quantity    = 1";

mysqli_query($db, $sql);
Header("location: xDietMix.php?cat=$cat");
