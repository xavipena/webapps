<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include './includes/dbConnect.inc.php';

// --------------------------------
// Save into daily user meals
// --------------------------------

$sql =  "select * from diet_user_list ".
        "where IDuser = ".$_SESSION['diet_user'];
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "update diet_user_selection set quantity = quantity + ".$row['quantity']." ".
            "where IDuser    = ".$_SESSION['diet_user'].
            "  and IDmeal    = ".$row['IDmeal'].
            "  and IDproduct = ".$row['IDproduct'];

    mysqli_query($db, $sql);
    if (mysqli_affected_rows($db) == 0) {

        $sql =  "insert into diet_user_selection set ".
                " IDuser    = ".$_SESSION['diet_user'].
                ",IDmeal    = ".$row['IDmeal'].
                ",IDmix     = 0".
                ",IDproduct = ".$row['IDproduct'].
                ",quantity  = ".$row['quantity'];
    
        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
    }
}
Header("location: xDietSelection.php");
