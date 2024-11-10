<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';

// --------------------------------
// Delete previous day, if any
// --------------------------------
$mealDate = $_GET['mdate']; 
$sql =  "delete from diet_user_meals where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." and date = '".$mealDate."'";
mysqli_query($db, $sql);

// --------------------------------
// Save into daily user meals
// --------------------------------

$output = "";
$sql =  "select us.IDproduct IDproduct, ".
        "       us.IDmeal IDmeal,".
        "       us.IDmix IDmix,".
        "       us.quantity quantity ".
        "from diet_user_selection us ".
        "left join diet_products pr ".
        "  on pr.IDproduct = us.IDproduct ".
        "left join diet_product_mix mx ".
        "  on mx.IDmix = us.IDmix ".
        "where us.IDuser = ".$_SESSION['diet_user'];

$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    if ($row['IDproduct'] > 0)
    {
        //echo "pr:".$row['IDproduct'].",";

        $sql = "select * from diet_product_data where IDproduct = ".$row['IDproduct']." and unit = 'Ration'";
        $res = mysqli_query($db, $sql);
        if ($prd = mysqli_fetch_array($res)) 
        {
            $sql =  "insert into diet_user_meals set ".
                    " IDuser    = ".$_SESSION['diet_user'].
                    ",IDperiod    = ".$_SESSION['diet_period'].
                    ",IDmeal    = ".$row['IDmeal'].
                    ",IDmix     = 0".
                    ",IDproduct = ".$row['IDproduct'].
                    ",quantity  = ".$row['quantity'].
                    ",calories  = ".$prd['energy'].
                    ",date      = '".$mealDate."'";
            if (!mysqli_query($db, $sql)) $output .= mysqli_error($db);
        }
    }
    else if ($row['IDmix'] > 0) {

        //echo "mx:".$row['IDmix'].",";

        $sql = "select * from diet_product_mix where IDmix = ".$row['IDmix'];
        $res = mysqli_query($db, $sql);
        if ($prd = mysqli_fetch_array($res)) 
        {
            $sql =  "insert into diet_user_meals set ".
                    " IDuser    = ".$_SESSION['diet_user'].
                    ",IDperiod  = ".$_SESSION['diet_period'].
                    ",IDmeal    = ".$row['IDmeal'].
                    ",IDmix     = ".$row['IDmix'].
                    ",IDproduct = 0".
                    ",quantity  = ".$row['quantity'].
                    ",calories  = ".$prd['energy'].
                    ",date      = '".$mealDate."'";
            if (!mysqli_query($db, $sql)) $output .= mysqli_error($db);
        }
    }
}
if ($output == "")
{
    $sql = "delete from diet_user_selection where IDuser = ".$_SESSION['diet_user'];
    mysqli_query($db, $sql);
    Header("location: xDietDay.php");
}
else
{
    echo $output;
}
