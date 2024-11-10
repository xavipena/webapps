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

$currentPass = $clean['currentPassword'];
$newPass = $clean['newPassword'];
$confirmPass = $clean['confirmPassword'];

$currentPass = md5($currentPass);
$newPass = md5($newPass);

//--- Query ------------------------ 

// if already exists, ignore
$sql =  "update diet_users set password = '$newPass' ".
        "where IDuser = ".$_SESSION['diet_user']." and password = '$currentPass'";

if (mysqli_query($db, $sql)) {
    
    if (mysqli_affected_rows($db) == 0) {

        $error = 1;
        Header("Location: xDietAccountPss.php?err=$error");
        exit();
    }
    Header("Location: xDietUser_1.php");
}
else {

    echo mysqli_error($db);
}
?>
