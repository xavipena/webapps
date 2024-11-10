<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
if (!isset($_SESSION['diet_user'])) {

    Header("location: xDietSelection.php");
}
include './includes/dbConnect.inc.php';

$meal = 1;
if (!empty($_SESSION['meal'])) 
{
    $meal = $_SESSION['meal']; 
} 

// --------------------------------
// Save into daily user meals
// --------------------------------

$sql =  "select * from diet_user_mix";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "update diet_user_selection set quantity = quantity + ".$row['quantity']." ".
            "where IDuser    = ".$_SESSION['diet_user'].
            "  and IDmeal    = ".$meal.
            "  and IDproduct = 0".
            "  and IDmix     = ".$row['IDmix'];

    mysqli_query($db, $sql);
    if (mysqli_affected_rows($db) == 0) {

        $sql =  "insert into diet_user_selection set ".
                " IDuser    = ".$_SESSION['diet_user'].
                ",IDmeal    = ".$meal.
                ",IDproduct = 0".
                ",IDmix     = ".$row['IDmix'].
                ",quantity  = ".$row['quantity'];
    
        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
    }
}
$sql = "delete from diet_user_mix where IDuser = ".$_SESSION['diet_user'];
mysqli_query($db, $sql);
Header("location: xDietSelection.php");
