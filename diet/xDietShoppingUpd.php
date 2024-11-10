<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$sql = "select * from diet_product_list where done = 1";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql = "update diet_product_stock set quantity = quantity + ".$row['quantity']." where IDproduct = ".$row['IDproduct'];
    mysqli_query($db, $sql);
    if (mysqli_affected_rows($db) == 0) 
    {
        $sql =  "insert into diet_product_stock set ".
                ",IDproduct = ".$row['IDproduct'].
                ",quantity  = ".$row['quantity'];

        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
    }
}
Header("Location: xDietShopping.php");
