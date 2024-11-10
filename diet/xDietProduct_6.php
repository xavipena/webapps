<?php
/*
    INSERT components
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";

include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

// ---- Params --------------

$prd = $clean['c0'];

// --------------------------
// Process for each component
// --------------------------
$eFound = false;
$sql = "select IDcomponent from diet_components";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $id = $row['IDcomponent'];
    $idKey = "c$id";
    $qty = $clean[$idKey];
    if ($qty > 0) {

        // --------------------------
        // Determine the operation
        // --------------------------
        $sql = "select IDproduct from diet_product_composition where IDproduct = $prd and IDcomponent = $id";
        $res = mysqli_query($db, $sql);
        if (mysqli_fetch_array($res)) {
            
            $sql =  "update diet_product_composition set ".
                    " quantity          = ".$qty.
                    "where IDproduct    = ".$prd.
                    "  and IDcomponent  = ".$row['IDcomponent'];
        }
        else {
            
            $sql =  "insert into diet_product_composition set".
                    " IDproduct     = ".$prd.
                    ",IDcomponent   = ".$row['IDcomponent'].
                    ",quantity      = ".$qty.
                    ",unit          = 'mg'".
                    ",per           = 100";
        }
        if (!mysqli_query($db, $sql)) {

            echo mysqli_error($db);
            echo "<br>".$sql;
            $eFound = true;
        }
    }
}
if ($eFound) exit;
Header("Location: xDietProductFull.php?prd=$prd");
