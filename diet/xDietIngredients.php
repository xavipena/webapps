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
    function validateForm(frm) {

        var err = false;

        if (frm == "dietRcp") {

            if (document.forms["dietRcp"]["dtRecipe"].value == "") err = true;
            else if (document.forms["dietRcp"]["dtDesc"].value == "") err = true;
        }
        if (frm == "dietRcp") {
        
            if (document.forms["dietIng"]["prd"].value == "") err = true;
            else if (document.forms["dietIng"]["qty"].value == 0) err = true;
        }
        if (err) alert("Cal omplir totes les caselles!");

        return err ? false : true;
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

// --------------------------------
// Show list
// --------------------------------

$ulist = "<div class='amanida'><ul class='amanida'>";
$numCols = 2;
$sql = "select count(*) from diet_ingredients where status = 'A'";
$result = mysqli_query($db, $sql);
$limit = round(mysqli_fetch_array($result)[0] / $numCols); 

// --------------------------------
// Get recipe ID
// --------------------------------

if (!empty($clean['recipe'])) 
{
    $recipe = $clean['recipe'];
    $sql = "select * from diet_recipes where IDrecipe = ".$recipe;
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        $rID   = $row['IDrecipe'];
        $rname = $row['name'];
        $rdesc = $row['description'];
    }
}
else
{
    $rID   = "";
    $rname = "";
    $rdesc = "";

    $recipe = "";
    $sql = "select IDrecipe from diet_recipes order by IDrecipe desc limit 1";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {

        $rID = $row[0] + 1;
    }
    else $rID = 0;
}


// --------------------------------
// Recipe form
// --------------------------------

?>
<form id="dietRcp" method="post" action="xDietIngredients_2.php" onsubmit="return validateForm('dietRcp')">

    <h2>Receptes</h2>
    <fieldset>
        <legend>Recepta</legend>
        <table>
            <tr><td>ID</td>
            <td><input type="number" value="<?php echo $rID?>" size=6 id="dtID" name="dtID" readonly></td></tr>
            <tr><td><label for="dtRecipe">Nom de la recepta</label></td>
            <td><input type="text" value="<?php echo $rname?>" id="dtRecipe" name="dtRecipe" size="50"></td></tr>
            <tr><td><label for="dtDesc">Descripció</label></td>
            <td><input type="text" value="<?php echo $rdesc?>" id="dtDesc" name="dtDesc" size="70"></td></tr>
        </table>
        <br>
        <?php if ($recipe == "") { ?>
            <input type="submit" value="Crea la recepta">
        <?php } ?>
    </fieldset>
</form>
<br>
<?php

// --------------------------------
// Ingredient form
// --------------------------------

?>
<form id="dietIng" method="post" action="xDietIngredients_1.php" onsubmit="return validateForm('dietIng')">
    <input type="hidden" value="<?php echo $rID?>" id="rcp" name="rcp">

    <h2>Ingredients</h2>
    <fieldset>
        <legend>Ingredient</legend>
        <table>
            <tr><td><label for="prd">Nom de la recepta</label></td>
            <td>
                <select id="prd" name="prd">
                    <?php 
                    $sql = "select * from diet_ingredients where status = 'A' order by name";
                    $result = mysqli_query($db, $sql);
                    while ($row = mysqli_fetch_array($result)) 
                    {
                        echo "<option value='".$row['IDingredient']."'>".$row['name']."</option>";
                    }                
                    ?>
                </select>
            </td></tr>
            <tr><td><label for="qty">Quantitat (en g)</label></td>
            <td><input type="number" value="100" id="qty" name="qty" size="5"></td></tr>
        </table>
        <br>
        <input type="submit" value="Afegeix l'ingredient">
    </fieldset>
    <input type="button" value="Ja està" onclick="location.href='xDietSelection.php'">
</form>

<?php
echo "<br>";

// --------------------------------
// Selected data table
// --------------------------------

echo "<table class='dietcard'>";
echo "<caption>Recepta</caption>";
echo "<thead><tr><th>Treu</th>".
            "<th>Ingredient</th>".
            "<th>Calories</th>".
            "<th>Greixos</th>".
            "<th>Saturats</th>".
            "<th>Sucre</th>".
            "<th>Sal</th>".
            "<th>Quantitat</th>".
            "<th>+</th>".
            "<th>-</th>".
            "</tr></thead>";

$pvalue = array(0.0,0.0,0.0,0.0,0.0);
$ptotal = array(0.0,0.0,0.0,0.0,0.0);

$sql =  "select * from diet_recipe_ingredients ";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) {
        
    $sql =  "select * from diet_ingredients where IDingredient = ".$row['IDingredient'];
    $res = mysqli_query($db, $sql);
    $inrow = mysqli_fetch_array($res);

    $pvalue[0] = round(($row['quantity'] / 100) * $inrow['energy'], 2);
    $pvalue[1] = round(($row['quantity'] / 100) * $inrow['fat'], 2);
    $pvalue[2] = round(($row['quantity'] / 100) * $inrow['saturates'], 2);
    $pvalue[3] = round(($row['quantity'] / 100) * $inrow['sugar'], 2);
    $pvalue[4] = round(($row['quantity'] / 100) * $inrow['salt'], 2);

    $paramString = "rcp=$rID&prd=".$row['IDingredient'];
    
    echo $rowStart."[<a href='xDietIngredientsDel.php?$paramString'>Treu</a>]";
    echo $newCol.$inrow['name']."</td>";
    for ($c = 0; $c < 5; $c++) {

        printf("<td class='number'>%.2f</td>",$pvalue[$c]);
        $ptotal[$c] += $pvalue[$c];
    }
    echo $newColNum.$row['quantity'];
    echo $newCol."[<a href='xDietIngredientsQty.php?$paramString&act=add'>+</a>]".
            $newCol."[<a href='xDietIngredientsQty.php?$paramString&act=sub'>-</a>]";
} 

echo "<tfoot>";
echo $rowStart;
echo "    </td><td></td>";
for ($c = 0; $c < 5; $c++) {

    printf("<td class='number'>%.2f</td>",$ptotal[$c]);
}
echo "<td></td><td></td><td></td>";
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