<?php
/*
 */
session_start();
include './includes/dbConnect.inc.php';

$_SESSION['diet_loss'] = $_POST['lossPerDay'];
$_SESSION['diet_limit'] = $_POST['lossLimit'];

$sql =  "update diet_users set".
        " lossKg       = ".$_POST['lossKg'].
        ",lossWeeks    = ".$_POST['lossWeeks'].
        ",lossPerDay   = ".$_POST['lossPerDay'].
        ",lossDiet     = ".$_POST['topDiet'].
        ",dietType     = ".$_POST['lossDiet'].
        ",limited      = ".$_POST['lossLimit'].
        " where IDuser = ".$_SESSION['diet_user'];

        echo $sql."<br>";
if (mysqli_query($db, $sql)) 
{
    Header("Location: xDietCalc_2.php");
}
echo mysqli_error($db);
echo "<br>".$sql;
?>
