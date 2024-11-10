<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$dsh = empty($clean['dsh']) ? 0 : $clean['dsh'];
$prd = empty($clean['prd']) ? 0 : $clean['prd'];
    
//--- Query ------------------------ 

$sql =  "delete from diet_dish_products ".
        "where IDdish     = ".$dsh.
        "  and IDproduct  = ".$prd;

if (!mysqli_query($db, $sql))
{
    echo mysqli_error($db);
    exit;
}
Header("Location: xDietDishes_1.php?dish=$dsh");
