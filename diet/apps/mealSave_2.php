<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    include "./includes/app.security.inc.php";

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

//--- Content -------------------------

foreach ($clean as $key => $dish) {

    //--- Query ------------------------ 

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
                "  and IDmeal    = ".$_SESSION['meal'].
                "  and IDproduct = ".$row['product'];

        mysqli_query($db, $sql);
        if (mysqli_affected_rows($db) == 0) {

            $sql =  "insert into diet_user_selection set ".
                    " IDuser    = ".$_SESSION['diet_user'].
                    ",IDmeal    = ".$_SESSION['meal'].
                    ",IDmix     = 0".
                    ",IDproduct = ".$row['product'].
                    ",quantity  = ".$row['qty'];

            if (!mysqli_query($db, $sql))
            {
                echo mysqli_error($db);
                exit;
            }
        }
    }
}
Header("location: meals.php");
