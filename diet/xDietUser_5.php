<?php
/*
Update user weight
*/
session_start();
include './includes/dbConnect.inc.php';

$date = $_POST['dtDate'];
$weight = $_POST['dtWeight'];

//--- Settings ------------------------

//--- functions -----------------------

function GoNext() {
    
    if (empty($_POST['fromApp'])) {

        Header("Location: xDietUser_1.php");
    }
    else {
        
        Header("Location: apps/weight.php");
    }
}

//--- Content ---------------------

$sql = "select IDuser from diet_user_data where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." and date = '".$date."'";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    $sql =  "update diet_user_data set".
            " weight      = ".$weight.
            " where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." and date = '".$date."'";

    if (mysqli_query($db, $sql)) GoNext();
}
else 
{
    $sql =  "insert into diet_user_data set".
            " IDuser        = ".$_SESSION['diet_user'].
            ",IDperiod      = ".$_SESSION['diet_period'].
            ",date          ='".$date."'".
            ",burned        = 0".
            ",basal         = 0".
            ",recommended   = 0".
            ",limited       = 0".
            ",weight        = ".$weight;
    
    if (mysqli_query($db, $sql)) GoNext();
}
echo mysqli_error($db);
echo "<br>".$sql;

