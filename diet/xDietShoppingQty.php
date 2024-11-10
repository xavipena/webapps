<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$sql    = "";
$where  = "where IDproduct = ".$clean['prd'];
if ($clean['act'] == "add")
{
    $sql = "update diet_product_list set quantity = quantity + 1 ";
} 
else
{
    $qry = "select quantity from diet_product_lit ".$where;
    $res = mysqli_query($db, $qry);
    if ($qty = mysqli_fetch_array($res)) 
    {
        if ($qty['quantity'] > 0)
        {
            $sql = "update diet_product_list set quantity = quantity - 1 ";
        }
    }
}
if ($sql != "")
{
    $sql .= $where;
    if (!mysqli_query($db, $sql))
    {
        echo mysqli_error($db);
        exit;
    }
}
Header("Location: xDietShopping.php");
