<?php
/*
 */
session_start();
include './includes/dbConnect.inc.php';

$_SESSION['diet_basal'] = $_POST['calBasal'];
$_SESSION['diet_recom'] = $_POST['calRecom'];
$_SESSION['diet_limit'] = $_POST['calLimit'];

$sql = "select IDuser from diet_users where IDuser = ".$_SESSION['diet_user'];
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    $sql =  "update diet_user_periods set".
            " target       ='".$_POST['target']."'".
            ",formula      ='".$_POST['calProfile']."'".
            ",weight       = ".$_POST['weight'].
            ",height       = ".$_POST['height'].
            ",age          = ".$_POST['age'].
            ",activity     ='".$_POST['activity']."'".
            ",basal        = ".$_POST['calBasal'].
            ",thermogenesis= ".$_POST['calThermo'].
            ",exercise     = ".$_POST['calAdjust'].
            ",recommended  = ".$_POST['calRecom'].
            //",limited      = ".$_POST['calLimit'].
            ",sysdate      ='".date("Y-m-d")."'".
            " where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];

    if (mysqli_query($db, $sql)) 
    {
        Header("Location: xDietUser_1.php");
    }
}
else 
{
    $name = $_SESSION['username'];
    $sql =  "insert into diet_user_periods set".
            " IDuser    = ".$_SESSION['diet_user'].
            ",IDperiod  = ".$_SESSION['diet_period'].
            ",name      ='".$name."'".
            ",target    ='".$_POST['target']."'".
            ",formula   ='".$_POST['calProfile']."'".
            ",weight    = ".$_POST['weight'].
            ",height    = ".$_POST['height'].
            ",age       = ".$_POST['age'].
            ",activity  ='".$_POST['activity']."'".
            ",basal        = ".$_POST['calBasal'].
            ",thermogenesis= ".$_POST['calThermo'].
            ",exercise     = ".$_POST['calAdjust'].
            ",recommended  = ".$_POST['calRecom'].
            ",limited      = 0".
            ",sysdate      ='".date("Y-m-d")."'";
    
    if (mysqli_query($db, $sql)) 
    {
        // Next step
        Header("Location: xDietCalc_2.php");
    }
}
echo mysqli_error($db);
echo "<br>".$sql;
?>
