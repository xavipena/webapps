<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$prd = empty($clean['prd']) ? 0 : $clean['prd'];
$prd = empty($clean['prd']) ? 0 : $clean['prd'];
    
//--- Query ------------------------ 

$sql =  "delete from diet_user_list ".
        "where IDuser     = ".$_SESSION['diet_user'].
        "  and IDmeal     = ".$meal.
        "  and IDproduct  = ".$prd;

if (!mysqli_query($db, $sql))
{
    echo mysqli_error($db);
    exit;
}
Header("Location: xDietCommon.php");
