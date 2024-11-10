<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';
include './includes/dietFunctions.inc.php';

$sql = "select value, type, session from diet_settings where IDsetting = ".$clean['id'];
$res = mysqli_query($db, $sql);
$row = mysqli_fetch_array($res);
$defValue = $row['value'];
$session = $row['session'];
$type = $row['type'];

$sql = "select value from diet_user_settings where IDuser = ".$_SESSION['diet_user']." and IDsetting = ".$clean['id'];
$res = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($res))
{
    $oldValue = $row['value'];
}
else
{
    $sql =  "insert into diet_user_settings set ".
            " IDuser    = ".$_SESSION['diet_user'].
            ",IDsetting = ".$clean['id'].
            ",value     = '$defValue'";
    mysqli_query($db, $sql);
}

$newValue = "";
if ($type == "bool")
{
    $newValue = $oldValue == "Y" ? "N" : "Y";
}

$sql = "update diet_user_settings set".
        " value = '".$newValue."'".
        " where IDuser = ".$_SESSION['diet_user']." and IDsetting = ".$clean['id'];

if (mysqli_query($db, $sql)) 
{
    // Set new value
    $_SESSION[$session] = $newValue;
    // should use the table to save the go back file... 
    Header("Location: ".ReadCallback($db));
}
echo mysqli_error($db)."<br>";
echo $sql;
