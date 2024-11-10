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
$mix = empty($clean['mix']) ? 0 : $clean['mix'];
$mel = empty($clean['mel']) ? 0 : $clean['mel'];
    
//--- Query ------------------------ 

if (isset($_SESSION['diet_user'])) 
{
    if ($_SESSION['diet_user'] != "") 
    {
        if ($prd > 0)
        {
            $sql ="delete from diet_user_selection ".
                "where IDuser     = ".$_SESSION['diet_user'].
                "  and IDproduct  = ".$prd;
                "  and IDmeal     = ".$mel;
        }
        if ($mix > 0)
        {
            $sql ="delete from diet_user_selection ".
                "where IDuser     = ".$_SESSION['diet_user'].
                "  and IDmix      = ".$mix;
                "  and IDmeal     = ".$mel;
        }
        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
      }
}
Header("Location: xDietSelection.php");
