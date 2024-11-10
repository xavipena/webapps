<?php
/*
 */
session_start();
$runner_id ="";
$page = "diet";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

//--- Params ----------------------- 

$completed = false;

//--- Query ------------------------ 

// diet settings
$sql = "delete from diet_user_settings where IDuser = ".$_SESSION['diet_user'];
if (mysqli_query($db, $sql)) {
    
    // diet selection
    $sql = "delete from diet_user_selection where IDuser = ".$_SESSION['diet_user'];
    if (mysqli_query($db, $sql)) {
    
        // diet periods
        $sql = "delete from diet_user_periods where IDuser = ".$_SESSION['diet_user'];
        if (mysqli_query($db, $sql)) {
    
            // diet mix
            $sql = "delete from diet_user_mix where IDuser = ".$_SESSION['diet_user'];
            if (mysqli_query($db, $sql)) {
    
                // diet meals
                $sql = "delete from diet_user_meals where IDuser = ".$_SESSION['diet_user'];
                if (mysqli_query($db, $sql)) {
        
                    // diet lists
                    $sql = "delete from diet_user_lists where IDuser = ".$_SESSION['diet_user'];
                    if (mysqli_query($db, $sql)) {
        
                        // diet data
                        $sql = "delete from diet_user_data where IDuser = ".$_SESSION['diet_user'];
                        if (mysqli_query($db, $sql)) {
        
                            // diet user
                            $sql = "delete from diet_users where IDuser = ".$_SESSION['diet_user'];
                            if (mysqli_query($db, $sql)) {

                                $completed = true;
                            }
                        }
                    }
                }
            }
        }
    }
}

if ($completed) {
    
    Header("Location: ./login/logout.php");
}
else {

    Header("Location: xDietAccountDel.php?err=1");
}
?>
