<?php
/*
Update user weight
*/
session_start();
include './includes/dbConnect.inc.php';

$PGC = $_GET['pgc'];

//--- Settings ------------------------

//--- functions -----------------------

//--- Content ---------------------

$sql =  "update diet_user_periods set".
        " PGC  = ".$PGC.
        " where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
if (mysqli_query($db, $sql)) {

    header("location: xDietCalc.php");
};
echo $sql;
