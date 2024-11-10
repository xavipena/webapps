<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
if (!isset($_SESSION['diet_user'])) {

    Header("location: xDietSelection.php");
}
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

foreach ($clean as $key => $value) {

    //--- Query ------------------------ 

    // if already exists, ignore
    $sql =  "insert ignore into diet_user_selection set ".
            " IDuser    = ".$_SESSION['diet_user'].
            ",IDmeal    = ".$_SESSION['meal'].
            ",IDproduct = 0".
            ",IDmix     = ".$value.
            ",quantity  = 1";
    mysqli_query($db, $sql);
}
Header("location: xDietSelection.php");
