<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';

$sql = "insert into diet_user_meals set".
        " IDuser      = ".$_POST['dtUser'].
        ",IDperiod    = ".$_SESSION['diet_period'].
        ",IDmeal      = ".$_POST['dtMeal'].
        ",IDproduct   = ".$_POST['dtProd'].
        ",quantity    = ".$_POST['dtQty'].
        ",calories    = ".$_POST['dtCals'].
        ",date        ='".$_POST['dtDate']."'";

if (mysqli_query($db, $sql)) 
{
    // if already exists, ignore
    $sql = "insert ignore into diet_user_data set".
            " IDuser      = ".$_POST['dtUser'].
            ",IDperiod    = ".$_SESSION['diet_period'].
            ",date        ='".$_POST['dtDate']."'".
            ",burned      = 0".
            ",basal       = ".$_SESSION['diet_basal'].
            ",recommended = ".$_SESSION['diet_recom'].
            ",limited     = ".$_SESSION['diet_limit'];

    mysqli_query($db, $sql);
    Header("Location: xDietDay_1.php?dt=".$_POST['dtDate']."&ml=".$_POST['dtMeal']);
}
echo $sql;

