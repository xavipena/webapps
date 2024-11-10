<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$cat = empty($clean['cat']) ? "" : $clean['cat'];

if ($cat != "") {

    if (isset($_SESSION['diet_user'])) 
    {
        $user = $_SESSION['diet_user'];
        if ($user != "") 
        {
            $sql ="delete from diet_user_mix where IDuser = $user and IDcat = ".$cat." and IDmix = ".$clean['prd'];
            if (!mysqli_query($db, $sql))
            {
                echo mysqli_error($db);
                exit;
            }
        }
    }
}
Header("Location: xDietMix.php");
