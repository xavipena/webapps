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
    include "./includes/background.inc.php";
?>
    <style>
        fieldset {
            min-width: 290px;
            border-color: #999;
            background-color: rgb(31,31,31,0.6 );
        }
        .fieldsetLarge {
            min-width: 605px;
        }
        .red {
            color: red;
        }
        .limited {
            margin: 0;
            justify-content: flex-start;
            max-width:800px;
        }
    </style>
    <script>
        var oldvalue = "";
        function SetNewType(f, p, m) 
        {
            // If x update session
            if (p == "x") {

                var d = document.getElementById("mealSelect_x").value;
                location.href="xDietSelection_6.php?meal=" + d;
                return;
            };

            var d = document.getElementById("mealSelect_" + f + p + m).value;
            params = "?prd=" + p + "&mix=" + m + "&meal=" + d + "&old=" + oldvalue;
            //console.log("calling: xDietSelection_2.php" + params);
            location.href="xDietSelection_2.php" + params;
        }
        function SaveMeal()
        {
            var d = document.getElementById("mealDate").value;
            location.href="xDietSelection_1.php?mdate=" + d;
        }
    </script>
</head>
<body>
<?php 
    //--- params ------------------------- 

    // Set in xDietMixAdd.php
    if (empty($_SESSION['cat'])) $_SESSION['cat'] = 0;
    if (empty($_SESSION['meal'])) $_SESSION['meal'] = 1;

    //--- config ------------------------- 

    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "diet_user_meals";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";

    // --------------------------------
    // Mount the meal selector
    // ID can be a products or a mix
    // $f is used to disambiguos same field in same form
    // --------------------------------

    function MealSelector($db, $f, $prd, $mix, $current) {

        $id = $f.$prd.$mix;
        $mealSelector = "<select onfocus='oldvalue = this.value;' onchange='SetNewType(\"$f\", \"$prd\", \"$mix\")' id='mealSelect_$id' name='mealSelect_$id'>";
        $sql ="select * from diet_meals order by IDmeal";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) 
        {
            $selected = $row['IDmeal'] == $current ? "selected" : ""; 
            $mealSelector .= "<option $selected value='".$row['IDmeal']."'>".$row['name']."</option>";
        }
        $mealSelector .= "</select>";
        return $mealSelector;
    }

    // --------------------------------
    // Quick icon
    // --------------------------------

    function QuickIcon($prd) {

        echo "<div class='divIcon'>";
        $list = explode( ",", $prd);
        foreach ($list as $l) {

            $l = trim($l);
            $image = getImage("diet", $l);
            echo "  <a href='https://diaridigital.net/diet/xDietSelectionAdd.php?prd=$l'>";
            echo "      <img class='imgIcon' src='$image'>";
            echo "  </a>";
        }
        echo "</div>";
    }

    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php"; 
    include "./includes/menu.inc.php"; 
    $menuType = MENU_SELECTION;
    $selectedMenuOption = 3;
    include "./includes/dietMenu.inc.php";

    if (empty($_SESSION['diet_user'])) {

        echo "No s'ha assignat cap usuari";
        echo "<br><a href='xDietUser_1.php'>Assigna usuari</a>";
        echo "</div></body></html>";
        exit;
    }

    // ----------------------------
    // Outter table
    echo "<table cellpadding='10'><tr><td colspan='2'>";
    // ----------------------------

    echo "<h3>Què has menjat avui?</h3>";
    echo "Un cop completat, grava-ho amb una data";

    // ----------------------------
    // Outter table
    echo "</td></tr>";
    echo "<tr><td>";
    // ----------------------------

    echo "<table class='dietcard'>";
    echo "    <thead>";
    echo "       <caption>Ingesta del dia</caption>";
    echo "       <tr>";
    echo "           <th>Treu</th>";
    echo "            <th>Producte</th>";
    echo "            <th>Ració</th>";
    echo "           <th>Calories</th>";
    echo "            <th>Sal</th>";
    echo "            <th>Sucre</th>";
    echo "            <th>Un.</th>";
    echo "            <th>+</th>";
    echo "            <th>-</th>";
    echo "            <th>Àpat</th>";
    echo "        </tr>";
    echo "    </thead>";

    $total = 0;
    $salt = 0;
    $sugar = 0;

// --------------------------------
// Display data
// --------------------------------

$aux = "";
$rows = 0;
$sql =  "select us.*, pr.*".
        "from diet_user_selection us ".
        "left join diet_products pr ".
        "  on pr.IDproduct = us.IDproduct ".
        "where us.IDuser = ".$_SESSION['diet_user']." order by us.IDmeal ";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    if (empty($aux)) $aux = $row['IDmeal'];
    if ($row['IDmeal'] != $aux)
    {
        $aux = $row['IDmeal'];
        echo "<tr><td colspan='10'>&nbsp;".$rowEnd;
    }

    $paramMel = "?mel=".$row['IDmeal'];

    if ($row['IDproduct'] > 0) 
    {
        $sql = "select * from diet_product_data where IDproduct = ".$row['IDproduct']." and unit = 'Ration'";
        $res = mysqli_query($db, $sql);
        if ($prd = mysqli_fetch_array($res)) 
        {
            $isSolid = $row['food'] == "Sòlid" ? "1" : "0";

            echo $rowStart."[<a href='xDietSelectionDel.php$paramMel&prd=".$row['IDproduct']."'>T</a>]";
            echo $newCol."<a href='javascript:GetDetails(".$row['IDproduct'].", $isSolid)'>".$row['name']."</a>";
            echo $newColNum.$prd['grams']." g";
            echo $newColNum.$prd['energy']." Kcal".
                $newColNum.$prd['salt']." g".
                $newColNum.$prd['sugar']." g".
                $newColNum.$row['quantity']; 

            echo $newCol."[<a href='xDietSelectionQty.php$paramMel&prd=".$row['IDproduct']."&act=add'>+</a>]".
                $newCol."[<a href='xDietSelectionQty.php$paramMel&prd=".$row['IDproduct']."&act=sub'>-</a>]".
                $newCol.MealSelector($db, $rows, $row['IDproduct'], 0, $row['IDmeal']).$rowEnd.PHP_EOL;
            $total += $prd['energy'] * $row['quantity']; 
            $salt += $prd['salt'];
            $sugar += $prd['sugar'];
            $rows +=1;
        }
    }
    if ($row['IDmix'] > 0) {

        if ($_SESSION['cat'] == 1) {

            // Sushi. De la taula de Mix

            $sql = "select * from diet_product_mix where IDmix = ".$row['IDmix'];
            $res = mysqli_query($db, $sql);
            if ($prd = mysqli_fetch_array($res)) {

                echo $rowStart."[<a href='xDietSelectionDel.php$paramMel&mix=".$row['IDmix']."'>Treu</a>]";
                echo $newCol.$prd['name'];
                echo $newColNum."peça";
                echo $newColNum.$prd['energy']." Kcal".
                    $newColNum."0.00 g".
                    $newColNum."0.00 g".
                    $newColNum.$row['quantity']; 
                echo $newCol."[<a href='xDietSelectionQty.php$paramMel&mix=".$row['IDmix']."&act=add'>+</a>]".
                    $newCol."[<a href='xDietSelectionQty.php$paramMel&mix=".$row['IDmix']."&act=sub'>-</a>]".
                    $newCol.MealSelector($db, $rows, 0, $row['IDmix'], $row['IDmeal']).$rowEnd;
                $total += $prd['energy'] * $row['quantity']; 
                $rows +=1;
            }
        }
        else {
            
            // Picoteo. És un producte normal

            $sql = "select * from diet_products where IDproduct = ".$row['IDmix'];
            $res = mysqli_query($db, $sql);
            $prd = mysqli_fetch_array($res);
            $name = $prd['name'];

            $sql = "select * from diet_product_data where IDproduct = ".$row['IDmix']." and unit = 'Ration'";
            $res = mysqli_query($db, $sql);
            if ($prd = mysqli_fetch_array($res)) {

                $isSolid = $row['food'] == "Sòlid" ? "1" : "0";

                echo $rowStart."[<a href='xDietSelectionDel.php$paramMel&mix=".$row['IDmix']."'>Treu</a>]";
                echo $newCol."<a href='javascript:GetDetails(".$row['IDmix'].", $isSolid)'>$name</a>";
                echo $newColNum.$prd['grams']." g";
                echo $newColNum.$prd['energy']." Kcal".
                    $newColNum.$prd['salt']." g".
                    $newColNum.$prd['sugar']." g".
                    $newColNum.$row['quantity']; 
    
                echo $newCol."[<a href='xDietSelectionQty.php$paramMel&mix=".$row['IDmix']."&act=add'>+</a>]".
                    $newCol."[<a href='xDietSelectionQty.php$paramMel&mix=".$row['IDmix']."&act=sub'>-</a>]".
                    $newCol.MealSelector($db, $rows, 0, $row['IDmix'], $row['IDmeal']).$rowEnd.PHP_EOL;
                $total += $prd['energy'] * $row['quantity']; 
                $salt += $prd['salt'];
                $sugar += $prd['sugar'];
                $rows +=1;
            }
        }
    }
}
if ($rows == 0) {

    echo "<tr><td colspan='9'>Sense àpats</td></tr>";
}
echo "</table>";

// ----------------------------
// Outter table
echo "</td><td>";
// ----------------------------

echo "<div class='container limited'>";

echo "Selecciona productes o menjars per calcular la ingesta:";


// ----------------------------
// Select meal type
// ----------------------------

echo "<div class='cardSelectorLarge'>";
echo "<fieldset class='fieldsetLarge'>";
echo "<legend>Àpat</legend>";
echo "<form id='frmB' method='post' action='xDietDishes.php'>";
$meal = empty($_SESSION['meal']) ? "" : $_SESSION['meal'];
echo MealSelector($db, "", "x", "", $meal);
echo "<br>Selecciona l'àpat per assignar el menjar";
echo "</form>";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// LLista de sempre
// ----------------------------

echo "<div class='cardSelectorLarge'>";
echo "<fieldset class='fieldsetLarge'>";
echo "<legend>El de sempre</legend>";
echo "<input type='button' value='Afegeix la llista' onclick='location.href=\"xDietCommon_3.php\"'>&nbsp;";
echo "<input type='button' value='Canvia la lista' onclick='location.href=\"xDietCommon.php\"'>";
echo "<br>La llista conté els productes que consumeixes diàriament";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// Select products
// ----------------------------

echo "<div class='cardSelector'>";
echo "<fieldset>";
echo "<legend>Productes</legend>";
echo "<form id='frmA' method='post' action='xDietProductsList.php'>";
echo "<input type='submit' value='Selecciona productes'>";
echo "<br>Productes individuals";
echo "</form>";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// Select dishes
// ----------------------------

echo "<div class='cardSelector'>";
echo "<fieldset>";
echo "<legend>Plats</legend>";
echo "<form id='frmB' method='post' action='xDietDishes.php'>";
echo "<input type='submit' value='Selecciona plats'>";
echo "<br>Plats ya definits amb els ingredients";
echo "</form>";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// Select salad
// ----------------------------

echo "<div class='cardSelector'>";
echo "<fieldset>";
echo "<legend>Amanida</legend>";
echo "<form id='frmC' method='post' action='xDietAmanides.php'>";
echo "<input type='submit' value='Fes-te una amanida'>";
echo "<br>Composició d'una amanida";
echo "</form>";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// Select sushi
// ----------------------------

echo "<div class='cardSelector'>";
echo "<fieldset>";
echo "<legend>Sushi</legend>";
echo "<form id='frmD' method='post' action='xDietMix.php?cat=1'>";
echo "<input type='submit' value='Avui sushi!'>";
echo "<br>Selecció de peces de sushi";
echo "</form>";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// Select picoteo
// ----------------------------

echo "<div class='cardSelector'>";
echo "<fieldset>";
echo "<legend>De picar</legend>";
echo "<form id='frmD' method='post' action='xDietMixAdd.php?cat=2'>";
echo "<input type='submit' value='Avui fem de picar'>";
echo "<br>Selecció de productes de un pica-pica";
echo "</form>";
echo "</fieldset>";
echo "</div>";

// ----------------------------
// Select begudes
// ----------------------------

echo "<div class='cardSelector'>";
echo "<fieldset>";
echo "<legend>Begudes</legend>";
echo "<form id='frmD' method='post' action='xDietProductsFresh.php?type=2' class='iconForm'>";
echo quickIcon("11, 9, 46");
echo "<div class='divIconButtons'>";
echo "  <input type='submit' value='Begudes'>";
echo "  <br>Selecció de begudes";
echo "</div>";
echo "</form>";
echo "</fieldset>";
echo "</div>";

echo "</div>";

// ----------------------------
// Outter table
echo "</td></tr>";
// ----------------------------

// ----------------------------
// Retrieve data
// ----------------------------

if (isset($_SESSION['diet_user'])) {
    
    if ($_SESSION['diet_user'] != "") {
        
        $sql = "select * from diet_users where IDuser = ".$_SESSION['diet_user'];
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) {
            
            $recommended = $row['recommended'];
            $lossDiet = $row['lossDiet'];
        }
    }
}
$sql ="select * from diet_topics where IDtopic = 3"; // sal
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    $recom_3 = $row['recommended'];
    $units_3 = $row['units'];
}
/*
$sql ="select * from diet_topics where IDtopic = 4"; // sucre
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    $recom_4 = $row['recommended'];
    $units_4 = $row['units'];
}
*/
// Sugar
// ----------
$user = $_SESSION['diet_user'];
$sql = "select * from diet_users where Iduser = ".$user;
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);

$diet_recom = $row['recommended'];
$diet_limit = $row['limited'];
$calsNeeded =  $diet_recom - $diet_limit;

$recomPercent = 5; // Recomanat per la OMS
$calsPerGram = 4;  // Calories per gram de sucre
$sugarLimit = round(round($calsNeeded * $recomPercent / 100, 0) / $calsPerGram, 0);

$recom_4 = $sugarLimit;
$units_4 = "g";


// ----------------------------
// Outter table
echo "<tr><td>"; 
// ----------------------------

echo "<div id='pData' class='details'>Informació nutricional</div>";

// ----------------------------
// Outter table
echo "</td><td>";
// ----------------------------

// ----------------------------
// totals
// ----------------------------

echo "<strong>Total diari: portes ".$total." Kcal de ".$recommended." Kcal"; 
if ($lossDiet > 0)
{
    echo ", ".$lossDiet." Kcal per control de dieta";
}
echo "</strong>";
echo "<br>";
echo "Et queden ".$recommended - $total." Kcal de ingesta per avui";
if ($lossDiet > 0)
{
    echo ", o ".$lossDiet - $total." Kcal per control de dieta";
}
echo "<br>";

$calDiff = $total - $recommended;
if ($total > 0) 
{
    if ($calDiff < 0) echo "Ara estàs per sota de les calories de ingesta diària<br>";
    if ($lossDiet <> 0)
    {
        $tolerance = $lossDiet * 0.1; // + 10%
        $lossDiet += $tolerance;
        if (abs($calDiff) > $lossDiet) echo "No és bo perdre més de ".$lossDiet." calories al dia<br>";
    }
}

$warning = $salt > $recom_3;
$wclass = $warning ? " class='red'" : "";
echo "<span$wclass>Sal: ".$salt." g</span>, recomanat $recom_3 g<br>";
if ($warning) echo "* Consum superior al recomanat (".$recom_3.$units_3.")<br>";

$warning = $sugar > $recom_4;
$wclass = $warning ? " class='red'" : "";
echo "<span$wclass>Sucre: ".$sugar." g</span>, recomanat $recom_4 g<br>";
if ($warning) echo "* Consum superior al recomanat (".$recom_4.$units_4.")<br>";

// --------------------------------
// Save into daily user meals
// --------------------------------
if ($rows > 0)
{
    echo "<br>";
    echo "<input type='button' value='Desau als meus àpats' onclick='SaveMeal()'>";
?>
    <label for="mealDate">Per la data:</label>
    <input type="date" id="mealDate" value="<?php echo date("Y-m-d")?>">
    <br><br>
    <script>
        const d = document.getElementById("mealSelect_x");
        <?php
        $meal = empty($_SESSION['meal']) ? "1" : $_SESSION['meal']; 
        echo "d.value = ".$meal;
        ?> 
    </script>
<?php

    // ----------------------------
    // Copy to user
    // restricted to admin user
    // ----------------------------

    if (!empty($_SESSION['diet_admin'])) {

        if ($_SESSION['diet_admin']== 1) {

            echo "<fieldset>";
            echo "<legend>Replica</legend>";
            echo "<form id='frmU' method='post' action='xDietSelection_5.php'>";
            echo "<br><input type='submit' value='Copia '> a l'usuari:&nbsp;";
            // ----------------------------
            // selector for users available
            // ----------------------------
            $sql = "select * from diet_users where IDuser <> ".$_SESSION['diet_user']." order by name";
            $result = mysqli_query($db, $sql);
            echo "<select name='IDuser'>";
            while ($row = mysqli_fetch_array($result))
            {
                echo "<option value='".$row['IDuser']."'>".$row['name']."</option>";
            }
            echo "</select></form>";
            echo "</fieldset>";
        }
    }

    // ----------------------------
    // Outter table
    echo "</td></tr><tr><td>";
    // ----------------------------


    // ----------------------------
    // Outter table
    echo "</td><td>";
    // ----------------------------

    echo "<div id='pRems' class='details'><h3>Recorda</h3>"; 
    echo "<span>";
    echo "Has posat tota la beguda?<br>"; 
    echo "Has posat el que has picat entre hores?<br>"; 
    echo "Cafès?<br>"; 
    echo "Has posat les salses de més?<br>"; 
    echo "Has posat el pa?<br>"; 
    echo "</span><br>";
    echo "</div>";
}

// ----------------------------
// Outter table
echo "</td></tr></table>";
// ----------------------------

include './includes/googleFooter.inc.php';
