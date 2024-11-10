<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';

$ID = $_POST['dtID'];
if ( $ID == 0) {

    $sql = "select IDrecipe from diet_recipes order by IDrecipe desc limit 1";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {

        $ID = $row['IDrecipe'] +1;
    }
    else $ID = 1;
}
// if already exists, ignore
$sql = "insert ignore into diet_recipes set".
        " IDrecipe      = ".$ID.
        ",name          ='".$_POST['dtRecipe']."'".
        ",description   ='".$_POST['dtDesc']."'";

if (mysqli_query($db, $sql)) 
{
    Header("Location: xDietIngredients.php?recipe=$ID");
}
echo $sql;
