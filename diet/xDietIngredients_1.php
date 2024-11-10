<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$rcp = $clean['rcp'];
$prd = $clean['prd'];
$qty = $clean['qty'];

if ($rcp == "0") Header("Location: xDietIngredients.php");

//--- Query ------------------------ 

// if already exists, ignore
$sql = "insert ignore into diet_recipe_ingredients set".
        " IDrecipe     = ".$rcp.
        ",IDingredient = ".$prd.
        ",quantity     = ".$qty;

mysqli_query($db, $sql);
Header("Location: xDietIngredients.php?recipe=$rcp");

