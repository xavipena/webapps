<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

//--- Content -------------------------

foreach ($clean as $key => $value) {

    //--- Query ------------------------ 

    // if already exists, ignore
    $sql =  "insert ignore into diet_user_selection set ".
            " IDuser    = ".$_SESSION['diet_user'].
            ",IDmeal    = ".$_SESSION['meal'].
            ",IDproduct = ".$value.
            ",IDmix     = 0".
            ",quantity  = 1";
    mysqli_query($db, $sql);
}
Header("location: meals.php");
