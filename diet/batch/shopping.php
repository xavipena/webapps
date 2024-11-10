<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    include "./includes/app.security.inc.php";

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

//--- Content -------------------------

$sql ="select * from diet_product_list where done = 1";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $sql =  "update diet_product_stock set ".
            " quantity  = quantity + ".$row['quantity']." ".
            "where IDproduct = ".$row['IDproduct'];
    mysqli_query($db, $sql);
    if (mysqli_affected_rows($db) == 0) {

        $sql =  "insert into diet_product_stock set ".
                " IDproduct = ".$row['IDproduct'].
                ",quantity  = ".$row['quantity'];
        if (mysqli_query($db, $sql)) {

            $sql = "delete from diet_product_list where IDproduct = ".$row['IDproduct']." and done = 1";
            mysqli_query($db, $sql);
        }
    }
}
Header("location: ../xDietShopping.php");
