<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';

// --------------------------------
// Delete previous selection, if any
// --------------------------------
$mealDate = $_GET['mdate']; 
$sql =  "delete from diet_user_selection where IDuser = ".$_SESSION['diet_user'];
mysqli_query($db, $sql);

// --------------------------------
// Save into daily user meals
// --------------------------------

$output = "";
$sql =  "select * from diet_user_meals ".
        "where IDuser = ".$_SESSION['diet_user']." and date = '".$mealDate."'";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "insert into diet_user_selection set ".
            " IDuser    = ".$_SESSION['diet_user'].
            ",IDproduct = ".$row['IDproduct'].
            ",quantity  = ".$row['quantity'].
            ",IDmeal    = ".$row['IDmeal'].
            ",IDmix     = ".$row['IDmix'];
    
    if (!mysqli_query($db, $sql)) $output .= mysqli_error($db);
}
if ($output == "")
{
    Header("location: xDietSelection.php");
}
else
{
    echo $output;
}
