<?php
/*
Update user weight
*/
session_start();
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";

$action = $_POST['op'];
$desc = $_POST['dtDesc'];
$from = $_POST['dtFrom'];
$to   = $_POST['dtTo'];

//--- Settings ------------------------

//--- functions -----------------------

//--- Content ---------------------

if ($action == FORM_ADD) {

    $period = 1;
    $sql = "select IDperiod from diet_periods where IDuser = ".$_SESSION['diet_user']." order by IDperiod desc limit 1";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {

        $period = $row['IDperiod'] + 1;
    }
    $sql =  "insert into diet_periods set".
            " IDuser        = ".$_SESSION['diet_user'].
            ",IDperiod      = $period".
            ",description   ='".$desc."'".
            ",beginDate     ='$from'".
            ",endDate       ='$to'";

    if (mysqli_query($db, $sql)) {

        $sql =  "insert into diet_user_periods set".
                " IDuser        = ".$_SESSION['diet_user'].
                ",IDperiod      = $period".
                ",target        =''".
                ",formula       =''".
                ",weight        = 0".
                ",height        = 0".
                ",age           = 0".
                ",activity      =''".
                ",dietType      = 0".
                ",basal         = 0".
                ",Thermogenesis = 0".
                ",exercise      = 0".
                ",recommended   = 0".
                ",limited       = 0".
                ",sysDate       ='".date("Y-m-d")."'".
                ",lossKg        = 0".
                ",lossWeeks     = 0".
                ",lossDiet      = 0".
                ",lossPerDay    = 0";

        if (mysqli_query($db, $sql)) {
        
            Header("Location: xDietUser_1.php");
            exit();
        }
    }
}
if ($action == FORM_EDIT) {

    $sql = "update diet_periods set".
            " description   ='".$desc."'".
            ",beginDate     ='$from'".
            ",endDate       ='$to'".
            " where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_POST['dtPeri'];

    if (mysqli_query($db, $sql)) {

        Header("Location: xDietUser_1.php");
        exit();
    }
}
echo mysqli_error($db);
echo "<br>".$sql;

