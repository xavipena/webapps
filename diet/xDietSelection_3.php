<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include './includes/dbConnect.inc.php';

$dish = $_GET['dish'];
$meal = 1;
if (isset($_SESSION['meal'])) 
{
    $meal = $_SESSION['meal']; 
} 
// --------------------------------
// Save into daily user meals
// --------------------------------

$sql =  "select dd.IDproduct product, dd.quantity qty, d.IDmeal meal ".
        "from diet_dish_products dd ".
        "join diet_dishes d ".
        "  on dd.IDdish = d.IDdish ".
        "where dd.IDdish = ".$dish;
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "update diet_user_selection set quantity = quantity + 1 ".
            "where IDuser    = ".$_SESSION['diet_user'].
            "  and IDmeal    = ".$meal.
            "  and IDproduct = ".$row['product'];

    //echo $sql."<br>";

    mysqli_query($db, $sql);
    if (mysqli_affected_rows($db) == 0) {

        $sql =  "insert into diet_user_selection set ".
                " IDuser    = ".$_SESSION['diet_user'].
                ",IDmeal    = ".$meal.
                ",IDmix     = 0".
                ",IDproduct = ".$row['product'].
                ",quantity  = ".$row['qty'];
    
        //echo $sql."<br>";

        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
    }
}
Header("location: xDietSelection.php");
