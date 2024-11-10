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

$sql = "select IDproduct from diet_products order by IDproduct desc limit 1";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$id = $row['IDproduct'] +1;

$comp = str_replace("'", ".", $clean['prComp']);   
$comp = str_replace("\xC9\x99", "", $comp);   

$name = str_replace("'", ".", $clean['prName']);
$desc = str_replace("'", ".", $clean['prDesc']);
$short = str_replace("'", ".", $clean['prShort']);

$sql =  "insert into diet_products set".
        " IDproduct     = ".$id.
        ",IDcat         = 0".
        ",name          ='".$name."'".
        ",description   ='".$desc."'".
        ",short         ='".$short."'".
        ",status        ='".$_POST['prStat']."'".
        ",ingredients   ='".$comp."'".
        ",brand         = ".$_POST['prBran'].
        ",type          ='".$_POST['prProc']."'".
        ",food          ='".$_POST['prCat']."'".
        ",IG            = 0".
        ",pick          = 0";

//echo $sql."<br>";

if (mysqli_query($db, $sql)) 
{
    $sql =  "insert into diet_product_data set".
            " IDproduct     = ".$id.
            ",unit          ='Standard'".
            ",grams         = ".str_replace(",",".",$_POST['c11']).
            ",energy        = ".str_replace(",",".",$_POST['c12']).
            ",fat           = ".str_replace(",",".",$_POST['c13']).
            ",saturates     = ".str_replace(",",".",$_POST['c14']).
            ",carbohydrate  = ".str_replace(",",".",$_POST['c15']).
            ",sugar         = ".str_replace(",",".",$_POST['c16']).
            ",fibre         = ".str_replace(",",".",$_POST['c17']).
            ",protein       = ".str_replace(",",".",$_POST['c18']).
            ",salt          = ".str_replace(",",".",$_POST['c19']).
            ",alcohol       = 0";

    if (mysqli_query($db, $sql)) 
    {
        $sql =  "insert into diet_product_data set".
                " IDproduct     = ".$id.
                ",unit          ='Ration'".
                ",grams         = ".str_replace(",",".",$_POST['c21']).
                ",energy        = ".str_replace(",",".",$_POST['c22']).
                ",fat           = ".str_replace(",",".",$_POST['c23']).
                ",saturates     = ".str_replace(",",".",$_POST['c24']).
                ",carbohydrate  = ".str_replace(",",".",$_POST['c25']).
                ",sugar         = ".str_replace(",",".",$_POST['c26']).
                ",fibre         = ".str_replace(",",".",$_POST['c27']).
                ",protein       = ".str_replace(",",".",$_POST['c28']).
                ",salt          = ".str_replace(",",".",$_POST['c29']).
                ",alcohol       = 0";

        if (mysqli_query($db, $sql)) 
        {
            Header("Location: xDietProduct_5.php?prd=$id");
        }
    }
}
echo $sql;

