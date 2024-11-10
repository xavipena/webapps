<?php
/*
    INSERT
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";

include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$sql =  "update diet_products set".
        " IG     = ".$clean['c1'].
        " where IDproduct = ".$clean['c0'];
if (mysqli_query($db, $sql))
{
    Header("Location: xDietProduct_5.php?prd=".$clean['c0']);
}
echo ShowSQL($sql);
echo mysqli_error($db);
