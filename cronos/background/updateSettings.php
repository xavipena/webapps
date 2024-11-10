<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$sql = "select value, type, session from crono_settings where IDsetting = ".$clean['id'];
$res = mysqli_query($db, $sql);
$row = mysqli_fetch_array($res);
$oldValue = $row['value'];
$session = $row['session'];

if ($row['type'] == "bool")
{
    $newValue = $oldValue == YES ? NO : YES;
}
else
{
    // if type value, set the values
    switch ($session)
    {
        case "coverpics":
            $newValue = $oldValue == "3" ? "5" : "3";
            break;
        case "numCols":
            $newValue = $oldValue == "3" ? "4" : "3";
            break;
        case "language":
            $newValue = $oldValue == "ca" ? "es" : "ca";
            $lang = $newValue;
            break;
    }
}

$sql = "update crono_settings set".
        " value     = '".$newValue."'".
        " where IDsetting = ".$clean['id'];

if (mysqli_query($db, $sql)) 
{
    // Set new value
    $_SESSION[$session] = $newValue;
    Header("Location: ../settings.php");
}
?>