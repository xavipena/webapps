<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';

$sql = "insert ignore into diet_user_list set".
        " IDuser      = ".$_SESSION['diet_user'].
        ",IDmeal      = ".$_POST['dtMeal'].
        ",IDproduct   = ".$_POST['dtProd'].
        ",quantity    = ".$_POST['dtQty'];

if (mysqli_query($db, $sql)) 
{
    Header("Location: xDietCommon.php");
}
echo $sql;
?>
