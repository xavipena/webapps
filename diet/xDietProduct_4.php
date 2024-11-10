<?php
/*
    UPDATE
 */
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";

include './includes/dbConnect.inc.php';
include './includes/googleSecurity.inc.php';

$comp = str_replace("'", ".", $clean['prComp']);   
$comp = str_replace("\xC9\x99", "", $comp);   

$name = str_replace("'", ".", $clean['prName']);
$desc = str_replace("'", ".", $clean['prDesc']);
$short = str_replace("'", ".", $clean['prShort']);

$sql =  "update diet_products set".
        " name          ='".$name."'".
        ",description   ='".$desc."'".
        ",short         ='".$short."'".
        ",status        ='".$clean['prStat']."'".
        ",ingredients   ='".$comp."'".
        ",brand         = ".$clean['prBran'].
        ",type          ='".$clean['prProc']."'".
        ",food          ='".$clean['prCat']."'".
        " where IDproduct = ".$clean['prID'];

//echo $sql."<br>";

if (mysqli_query($db, $sql)) 
{
    $sql =  "update diet_product_data set".
            " grams         = ".str_replace(",",".",$_POST['c11']).
            ",energy        = ".str_replace(",",".",$_POST['c12']).
            ",fat           = ".str_replace(",",".",$_POST['c13']).
            ",saturates     = ".str_replace(",",".",$_POST['c14']).
            ",carbohydrate  = ".str_replace(",",".",$_POST['c15']).
            ",sugar         = ".str_replace(",",".",$_POST['c16']).
            ",fibre         = ".str_replace(",",".",$_POST['c17']).
            ",protein       = ".str_replace(",",".",$_POST['c18']).
            ",salt          = ".str_replace(",",".",$_POST['c19']).
            ",alcohol       = 0".
            " where IDproduct = ".$clean['prID'].
            "   and unit      ='Standard'";

    if (mysqli_query($db, $sql)) 
    {
        $sql =  "update diet_product_data set".
                " grams         = ".str_replace(",",".",$_POST['c21']).
                ",energy        = ".str_replace(",",".",$_POST['c22']).
                ",fat           = ".str_replace(",",".",$_POST['c23']).
                ",saturates     = ".str_replace(",",".",$_POST['c24']).
                ",carbohydrate  = ".str_replace(",",".",$_POST['c25']).
                ",sugar         = ".str_replace(",",".",$_POST['c26']).
                ",fibre         = ".str_replace(",",".",$_POST['c27']).
                ",protein       = ".str_replace(",",".",$_POST['c28']).
                ",salt          = ".str_replace(",",".",$_POST['c29']).
                ",alcohol       = 0".
                " where IDproduct = ".$clean['prID'].
                "   and unit      ='Ration'";
    
        if (mysqli_query($db, $sql)) 
        {
            Header("Location: xDietProduct_5.php?prd=".$clean['prID']);
        }
    }
}
echo $sql;
