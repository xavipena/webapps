<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$newValue = $_POST["i".$clean['id']];

$sql =  "update crono_settings set".
        " value     = ".$newValue.
        " where IDsetting = ".$clean['id'];

if (mysqli_query($db, $sql)) 
{
    Header("Location: settings.php");
}
?>