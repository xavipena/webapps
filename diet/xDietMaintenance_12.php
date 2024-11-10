<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$prd = $clean['prd'];
$cat = $clean['cat'];

// if already exists, ignore
$sql =  "update diet_products set ".
        " IDcat     = $cat ".
        "where IDproduct = $prd";

mysqli_query($db, $sql);
Header("Location: xDietMaintenance_1.php");
?>