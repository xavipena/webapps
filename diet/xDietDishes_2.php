<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';

$ID = $_POST['dtID'];
if ( $ID == 0) {

    $sql = "select IDdish from diet_dishes order by IDdish desc limit 1";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {

        $ID = $row['IDdish'] +1;
    }
    else $ID = 1;
}
// if already exists, ignore
$sql = "insert ignore into diet_dishes set".
        " IDdish        = ".$ID.
        ",IDmeal        = ".$_POST['dtMeal'].
        ",name          ='".$_POST['dtDish']."'".
        ",description   ='".$_POST['dtDesc']."'";

mysqli_query($db, $sql);

$sql = "insert into diet_dish_products set".
        " IDdish      = ".$ID.
        ",IDproduct   = ".$_POST['dtProd'].
        ",quantity    = ".$_POST['dtQty'];

if (mysqli_query($db, $sql)) 
{
    Header("Location: xDietDishes_1.php?dish=".$ID);
}
echo $sql;
?>
