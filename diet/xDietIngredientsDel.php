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
$rcp = empty($clean['rcp']) ? 0 : $clean['rcp'];
    
//--- Query ------------------------ 

$sql =  "delete from diet_recipe_ingredients ".
        "where IDrecipe     = ".$rcp.
        "  and IDingredient = ".$prd;

if (mysqli_query($db, $sql))
{
    Header("Location: xDietIngredients.php?recipe=$rcp");
}
echo mysqli_error($db);
