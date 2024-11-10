<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';

if (isset($_SESSION['diet_user'])) 
{
    if ($_SESSION['diet_user'] != "") 
    {
        $sql ="delete from diet_user_salad where IDuser = ".$_SESSION['diet_user']." and IDproduct  = ".$_GET['prd'];
        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
      }
}
Header("Location: xDietAmanides.php");
