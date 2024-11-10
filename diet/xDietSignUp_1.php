<?php
/*
Update user weight
*/
session_start();
include './includes/dbConnect.inc.php';

$name = $_POST['dtName'];

$sql = "select IDuser from diet_users where name = '$name'";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    Header("Location: xDietSignUp.php?err=1");
}
else 
{
    $sql = "select IDuser from diet_users order by IDuser desc limit 1";
    $result = mysqli_query($db, $sql);
    $newID = mysqli_fetch_array($result)[0];
    $newID += 1;

    $sql =  "insert into diet_users set".
        " IDuser        = $newID".
        ",name          ='$name'".
        ",target        =''".
        ",formula       =''".
        ",gender        =''".
        ",weight        = 0".
        ",height        = 0".
        ",age           = 0".
        ",activity      =''".
        ",dietType      = 0".
        ",basal         = 0".
        ",Thermogenesis = 0".
        ",exercise      = 0".
        ",recommended   = 0".
        ",limited       = 0".
        ",sysdate       = '".date("Y-m-d")."'".
        ",lossKg        = 0".
        ",lossWeeks     = 0".
        ",lossDiet      = 0".
        ",lossPerDay    = 0";

    if (mysqli_query($db, $sql)) 
    {
        // Next step
        Header("Location: xDietUser_1.php");
    }
}
echo mysqli_error($db);
echo "<br>".$sql;
