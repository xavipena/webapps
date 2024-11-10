<?php
/*
    INSERT
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";

include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$prd = empty($clean['prd']) ? "" : $clean['prd'];
if ($prd == "") {

    Header("Location: xDietProducts.php");
}

// get ingredient new key

$sql = "select IDingredient from diet_ingredients order by IDingredient desc limit 1";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {
    
    $id = $row['IDingredient'] +1;
}
else $id = 1;

// get source product

$sql = "select * from diet_products where IDproduct = $prd";
$res = mysqli_query($db, $sql);
if ($prod = mysqli_fetch_array($res)) {
    
    // get product data

    $sql = "select * from diet_product_data where IDproduct = $prd and unit = 'Standard'";
    $re2 = mysqli_query($db, $sql);
    if ($pdat = mysqli_fetch_array($re2)) {
        
        // insert new ingredient
        
        $sql =  "insert into diet_ingredients set".
                " IDingredient  = ".$id.
                ",IDcat         = 0".
                ",name          ='".str_replace("'", ".", $prod['name'])."'".
                ",description   ='".str_replace("'", ".", $prod['description'])."'".
                ",status        ='A'".
                ",grams         = ".$pdat['grams'].
                ",energy        = ".$pdat['energy'].
                ",fat           = ".$pdat['fat'].
                ",saturates     = ".$pdat['saturates'].
                ",carbohydrate  = ".$pdat['carbohydrate'].
                ",sugar         = ".$pdat['sugar'].
                ",fibre         = ".$pdat['fibre'].
                ",protein       = ".$pdat['protein'].
                ",salt          = ".$pdat['salt'].
                ",alcohol       = ".$pdat['alcohol'];

        if (mysqli_query($db, $sql)) 
        {
            Header("Location: xDietProduct_5.php?prd=$id");
        }
    }
}
Header("Location: xDietProducts.php");
