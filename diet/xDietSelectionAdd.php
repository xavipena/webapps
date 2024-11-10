<?php
/*
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$prd = empty($clean['prd']) ? 0 : $clean['prd'];
$meal = !empty($_SESSION['meal']) ? $_SESSION['meal'] : $meal = 8; // Varis

//--- Functions -------------------- 

include './includes/dietFunctions.inc.php';

//--- Query ------------------------ 

if (isset($_SESSION['diet_user'])) 
{
    if ($_SESSION['diet_user'] != "") 
    {
        $sql =  "select count(*) from diet_user_selection ".
                "where IDuser    = ".$_SESSION['diet_user'].
                "  and IDproduct = ".$prd.
                "  and IDmeal = ".$meal;

        $result = mysqli_query($db, $sql);
        if (mysqli_fetch_array($result)[0] > 0) 
        {
            $sql =  "update diet_user_selection set quantity = quantity +1 ".
                    "where IDuser    = ".$_SESSION['diet_user'].
                    "  and IDproduct = ".$prd.
                    "  and IDmeal = ".$meal;
        }
        else
        {
            $sql =  "insert into diet_user_selection set".
                    " IDuser    = ".$_SESSION['diet_user'].
                    ",IDproduct = ".$prd.
                    ",IDmeal    = ".$meal.
                    ",IDmix     = 0".
                    ",quantity  = 1";
        }
        if (!mysqli_query($db, $sql))
        {
            echo mysqli_error($db);
            exit;
        }
    }
}
Header("Location: xDietSelection.php");
