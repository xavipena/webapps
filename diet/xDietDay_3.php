<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';

$sql =  "update diet_user_data set".
        " burned        = ".$_POST['dtBurn'].
        " where IDuser = ".$_SESSION['diet_user'].
        "   and IDperiod = ".$_SESSION['diet_period'].
        "   and date = '".$_POST['dtDate']."'";

if (mysqli_query($db, $sql)) 
{
    if (mysqli_affected_rows($db) == 0)
    {
        $sql =  "insert into diet_user_data set".
                " IDuser    = ".$_SESSION['diet_user'].
                " IDperiod  = ".$_SESSION['diet_period'].
                ",date      = '".$_POST['dtDate']."'".
                ",burned    = ".$_POST['dtBurn'].
                ",basal = 0, recommended = 0, limited = 0, weight = 0";

        if (mysqli_query($db, $sql)) 
        {
            Header("Location: xDietDay.php");
        }
    }
    Header("Location: xDietDay.php");
}
echo $sql."<br>";
echo mysqli_error($db);
