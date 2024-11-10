<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent, #1f1f1f 80%), url("./images/pexels-icon0-1213859.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<?php 

//--- Settings ------------------------

if (!empty($clean['mealSelect_z'])) $_SESSION['meal'] = $clean['mealSelect_z'];
$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions -------------------- 


//--- new content -------------------- 

//echo "<div class='dietPageContainer'>";
include "./includes/settingsEnd.inc.php";
include "./includes/menu.inc.php"; 

echo "<h2>Ingredients</h2>";

// --------------------------------
// Show list
// --------------------------------

$ulist = "<div class='amanida'><ul class='amanida'>";
$numCols = 2;
$sql = "select count(*) from diet_products where status = 'A' and salad = 1";
$result = mysqli_query($db, $sql);
$limit = round(mysqli_fetch_array($result)[0] / $numCols); 

$c = 0;
echo "<div class='container'>";
echo $ulist;
$sql = "select * from diet_products where status = 'A' and salad = 1 order by short";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    if ($c == $limit) echo "</ul></div>$ulist";
    echo "<li><a href='xDietAmanida_1.php?prd=".$row['IDproduct']."'>".$row['short']."</a></li>";
    $c += 1;
}
echo "</ul></div></div><br>";

// --------------------------------
// Selected data table
// --------------------------------

echo "<table class='dietcard'>";
echo "<caption>Composició de l'amanida</caption>";
echo "<thead><tr>".
            "<th>Treu</th>".
            "<th>Producte</th>".
            "<th>Calories</th>".
            "<th>Greixos</th>".
            "<th>Saturats</th>".
            "<th>Sucre</th>".
            "<th>Sal</th>".
    "</tr></thead>";

$pvalue = array(0.0,0.0,0.0,0.0,0.0);
$ptotal = array(0.0,0.0,0.0,0.0,0.0);

$sql =  "select dd.Idproduct prod, dd.quantity qty, ".
                " dp.name pname ".
        "from diet_user_salad dd ".
        "join diet_products dp on dp.IDproduct = dd.IDproduct ".
        "where dd.IDuser = ".$_SESSION['diet_user'];
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {

    $insql =  "select dpd.energy energy, dpd.fat fat, dpd.saturates saturates, dpd.sugar sugar, dpd.salt salt ". 
                "from diet_product_data dpd ".
                "where dpd.IDproduct = ".$row['prod']." and dpd.unit = 'Ration'";
    $inresult = mysqli_query($db, $insql);
    while ($inrow = mysqli_fetch_array($inresult)) {
        
        $pvalue[0] = round($row['qty'] * $inrow['energy'], 2);
        $pvalue[1] = round($row['qty'] * $inrow['fat'], 2);
        $pvalue[2] = round($row['qty'] * $inrow['saturates'], 2);
        $pvalue[3] = round($row['qty'] * $inrow['sugar'], 2);
        $pvalue[4] = round($row['qty'] * $inrow['salt'], 2);
        
        echo $rowStart."[<a href='xDietAmanidaDel.php?prd=".$row['prod']."'>Treu</a>]";
        echo $newCol.$row['pname']."</td>";
        for ($c = 0; $c < 5; $c++) {

            printf("<td class='number'>%.2f</td>",$pvalue[$c]);
            $ptotal[$c] += $pvalue[$c];
        }
    }
}

echo "<tfoot>";
echo $rowStart;
echo "    </td><td></td>";
for ($c = 0; $c < 5; $c++) {

    echo "<td class='number'>".$ptotal[$c]."</td>";
}
echo "</tr>";
echo "</tfoot>";

echo "</table>";
echo "<br>";
if (isset($_SESSION['diet_user'])) {

    if ($_SESSION['diet_user'] != "") {
        
        echo "<input type='button' value='Afegeix a la meva selecció' onclick='location.href=\"xDietAmanida_2.php\"'>&nbsp;";
    }
}
echo "&nbsp;<input type='button' value='Neteja' onclick='location.href=\"xDietAmanida_3.php\"'>";

include './includes/googleFooter.inc.php';
?>