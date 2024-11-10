<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$photo = empty($clean['photo']) ? "" : $clean['photo'];
if (empty($photo)) 
{
    header("location: selectSelected.php");
}

$sql = "delete from crono_sel_work where IDphoto = $photo";
if (!mysqli_query($db, $sql)) 
{
    echo mysqli_error($db);
}

if ($_SESSION['deletion'] == YES)
{
    header("location: selectSelected.php");
}
?>