<?php
/*
Update user weight
*/
session_start();
include '.././includes/dbConnect.inc.php';

// Sanitize input
//-----------------------------------------------------------------------------
$clean = array();
foreach(array_keys($_REQUEST) as $key)
{
    $clean[$key] = mysqli_real_escape_string($db, $_REQUEST[$key]);
}

$mel = empty($clean['dtMel']) ? "" : $clean['dtMel'];
$prd = empty($clean['dtPrd']) ? "" : $clean['dtPrd'];
$dsh = empty($clean['dtDsh']) ? "" : $clean['dtDsh'];

//--- functions -----------------------

function AddProduct($db, $mel, $prd) {

    $sql =  "select count(*) from diet_user_selection ".
            "where IDuser    = ".$_SESSION['diet_user'].
            "  and IDproduct = ".$prd.
            "  and IDmeal    = ".$mel;

    $result = mysqli_query($db, $sql);
    if (mysqli_fetch_array($result)[0] > 0) 
    {
        $sql =  "update diet_user_selection set quantity = quantity + 1 ".
                "where IDuser    = ".$_SESSION['diet_user'].
                "  and IDproduct = ".$prd.
                "  and IDmeal = ".$mel;
    }
    else
    {
        $sql =  "insert into diet_user_selection set".
                " IDuser    = ".$_SESSION['diet_user'].
                ",IDproduct = ".$prd.
                ",IDmeal    = ".$mel.
                ",IDmix     = 0".
                ",quantity  = 1";
    }
    if (!mysqli_query($db, $sql))
    {
        echo mysqli_error($db);
        exit;
    }
}

function AddProducts($db, $mel, $dsh) {

    $sql =  "select dd.IDproduct product, dd.quantity qty, d.IDmeal meal ".
            "from diet_dish_products dd ".
            "join diet_dishes d ".
            "  on dd.IDdish = d.IDdish ".
            "where dd.IDdish = ".$dsh;

    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        AddProduct($db, $mel, $row['product']);
    }
}

//--- Content -------------------------

if (isset($_SESSION['diet_user'])) 
{
    if ($_SESSION['diet_user'] != "") 
    {
        if ($prd != "") {
            
            AddProduct($db, $mel, $prd);
        }
        else if ($dsh != "") {

            AddProducts($db, $mel, $dsh);
        }
    }
}
header("location: meals.php");