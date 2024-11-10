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
<script>
    function CalcCalories() {

        var grads = document.getElementById("grd").value;
        var qty = document.getElementById("qty").value;

        var calories = grads * qty;
        calories = Math.round((calories * 0.8) / 100, 2);
        document.getElementById("calories").value = calories;

        var prd = document.getElementById("prd").value;
        document.location.href = "xDietAlcohol_1.php?prd=" + prd;
    }
</script>
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

$numCols = 2;
$limit = 5;
$c = 0;


$pvalue = array(0.0,0.0,0.0,0.0,0.0);
$ptotal = array(0.0,0.0,0.0,0.0,0.0);
?>
<form id="frmCalcCals">
    <input type="hidden" name="id" value="">
    <div class="formClass">
        <label for="lprd">Beguda</label>
        <select class="select-input" name="lprd" id="prd">
            <option value="1">Cervesa</option>
            <option value="2">Vi</option>
            <option value="3">Licor</option>
            <option value="4">Combinat</option>
        </select>
        <label for="lgrd">Graduació en graus</label>
        <input type="number" class="number-input" name="lgrd" id="grd" step="any" value="0">
        <label for="lqty">Quantitat en ml o cc</label>
        <input type="number" class="number-input" name="lqty" id="qty" value="0">
        <label for="lcalories">Calories</label>
        <input type="text" class="number-input" name="lcalories" id="calories" readonly value="0">

        <input type="button" value="Calcula i desa" onclick="CalcCalories()">
    </div>
</form>
<br>
<?php    

// --------------------------------
// Selected data table
// --------------------------------

echo "<table class='dietcard'>";
echo "<caption>Beguda alcohòlica</caption>";
echo "<thead><tr>".
            "<th>Treu</th>".
            "<th>Beguda</th>".
            "<th>Calories</th>".
    "</tr></thead>";

$sql =  "select * from diet_user_drinks ";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {
        
    $pvalue[0] = round($row['qty'] * $inrow['energy'], 2);
    
    echo $rowStart."[<a href='xDietAlcoholDel.php?prd=".$row['prod']."'>Treu</a>]";
    echo $newCol.$row['pname']."</td>";
    for ($c = 0; $c < 5; $c++) {

        printf("<td class='number'>%.2f</td>",$pvalue[$c]);
        $ptotal[$c] += $pvalue[$c];
    }
}

echo "<tfoot>";
echo $rowStart;
echo "    </td><td></td>";
for ($c = 0; $c < 1; $c++) {

    echo "<td class='number'>".$ptotal[$c]."</td>";
}
echo "</tr>";
echo "</tfoot>";

echo "</table>";
echo "<br>";
if (isset($_SESSION['diet_user'])) {

    if ($_SESSION['diet_user'] != "") {
        
        echo "<input type='button' value='Afegeix a la meva selecció' onclick='location.href=\"xDietAlcohol_2.php\"'>&nbsp;";
    }
}
echo "<input type='button' value='Neteja' onclick='location.href=\"xDietAlcohol_3.php\"'>";

include './includes/googleFooter.inc.php';
?>