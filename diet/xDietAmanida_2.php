<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include './includes/dbConnect.inc.php';

$meal = 1;
if (!empty($_SESSION['meal'])) 
{
    $meal = $_SESSION['meal']; 
} 

// --------------------------------
// Save into daily user meals
// --------------------------------

$sql =  "select * from diet_user_salad where IDuser = ".$_SESSION['diet_user'];
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "update diet_user_selection set quantity = quantity + 1 ".
            "where IDuser    = ".$_SESSION['diet_user'].
            "  and IDmeal    = ".$meal.
            "  and IDproduct = ".$row['IDproduct'];

    mysqli_query($db, $sql);
    if (mysqli_affected_rows($db) == 0) 
    {
        $sql =  "insert into diet_user_selection set ".
                " IDuser    = ".$_SESSION['diet_user'].
                ",IDmeal    = ".$meal.
                ",IDproduct = ".$row['IDproduct'].
                ",IDmix     = 0".
                ",quantity  = 1";
    
        //echo $sql."<br>";

        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
    }
}
Header("location: xDietSelection.php");
