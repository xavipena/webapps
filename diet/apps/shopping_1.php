<?php
/*
Update user weight
*/
session_start();
include '.././includes/dbConnect.inc.php';

$prd = $_POST['dtPrd'];
$qty = $_POST['dtQty'];

$sql = "select * from diet_product_list where IDproduct = $prd";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    $sql =  "update diet_product_list set".
            " quantity  = ".$qty.
            ",done      = 0".
            " where IDproduct = $prd";

    if (mysqli_query($db, $sql)) 
    {
        Header("Location: shopping.php");
    }
}
else 
{
    $sql =  "insert into diet_product_list set".
            " IDproduct     = $prd".
            ",quantity      = $qty".
            ",done          = 0";
    
    if (mysqli_query($db, $sql)) 
    {
        Header("Location: shopping.php");
    }
}
echo mysqli_error($db);
echo "<br>".$sql;

