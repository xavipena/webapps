<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$cat = empty($clean['cat']) ? "" : $clean['cat'];

if ($cat != "") {

    $sql = "";
    if (isset($_SESSION['diet_user'])) 
    {
        if ($_SESSION['diet_user'] != "") 
        {
            $where = "where IDuser = ".$_SESSION['diet_user']." and IDcat = $cat and IDmix = ".$clean['mix'];
            if ($clean['act'] == "add")
            {
                $sql = "update diet_user_mix set quantity = quantity + 1 ";
            } 
            else
            {
                $qry = "select quantity from diet_user_mix ".$where;
                $res = mysqli_query($db, $qry);
                if ($qty = mysqli_fetch_array($res)) 
                {
                    if ($qty['quantity'] > 0)
                    {
                        $sql = "update diet_user_mix set quantity = quantity - 1 ";
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
}
Header("Location: xDietMix.php?cat=".$cat);
