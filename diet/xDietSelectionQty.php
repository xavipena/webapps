<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$sql = "";
if (isset($_SESSION['diet_user'])) 
{
    if ($_SESSION['diet_user'] != "") 
    {
        // check if product or mix
        if (!empty($clean['prd'])) $product = " and IDproduct = ".$clean['prd'];
        if (!empty($clean['mix'])) $product = " and IDmix = ".$clean['mix'];

        $where = "where IDuser = ".$_SESSION['diet_user'].$product." and IDmeal = ".$clean['mel'];
        if ($clean['act'] == "add")
        {
            $sql = "update diet_user_selection set quantity = quantity + 1 ";
        } 
        else
        {
            $qry = "select quantity from diet_user_selection ".$where;
            $res = mysqli_query($db, $qry);
            if ($qty = mysqli_fetch_array($res)) 
            {
                if ($qty['quantity'] > 0)
                {
                    $sql = "update diet_user_selection set quantity = quantity - 1 ";
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
    }
}
Header("Location: xDietSelection.php");
