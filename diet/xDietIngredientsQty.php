<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$rcp = $clean['rpc'];
$prd = $clean['prd'];


$sql = "";
$qry = "select quantity from diet_recipe_ingredients where IDrecipe = $rcp and IDingredient = $prd";
$res = mysqli_query($db, $qry);
if ($qty = mysqli_fetch_array($res)) 
{
    switch ($clean['act']) {

        case "add":
            $sql = "update diet_recipe_ingredients set quantity = quantity + 1 ";
            break;

        case "sub":
            if ($qty['quantity'] > 0)
            {
                $sql = "update diet_user_selection set quantity = quantity - 1 ";
            }
            break;
    }
}

if ($sql != "")
{
    if (!mysqli_query($db, $sql))
    {
        echo mysqli_error($db);
        exit;
    }
}
Header("Location: xDietIngredients.php");
