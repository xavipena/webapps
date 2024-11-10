<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$otherUser = $clean['IDuser'];

// --------------------------------
// Save into daily user meals
// --------------------------------

$sql =  "select * from diet_user_selection ".
        "where IDuser = ".$_SESSION['diet_user'];
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "insert ignore into diet_user_selection set ".
            " IDuser    = ".$otherUser.
            ",IDproduct = ".$row['IDproduct'].
            ",IDmix     = ".$row['IDmix'].
            ",quantity  = ".$row['quantity'].
            ",IDmeal    = ".$row['IDmeal'];

    if (!mysqli_query($db, $sql))
    {
        echo mysqli_error($db);
        exit;
    }
}
Header("location: xDietSelection.php");
