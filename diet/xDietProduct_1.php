<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleSecurity.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/settingsStart.inc.php';
    include "./includes/sideMenuHover_1.inc.php";
    include "./includes/background.inc.php";
?>
     <style>
        #recalc {
            position: relative;
            top: -300px;
            left: 625px; /* table size */
            width: 300px;
        }
    </style>
    <script>
        function ListRelated() {

            
        }
        
        function validateForm() {

            var err = false;

            if (document.forms["prodAdd"]["prName"].value == "") err = true;
            else if (document.forms["prodAdd"]["prDesc"].value == "") err = true;
            else if (document.forms["prodAdd"]["prComp"].value == "") err = true;

            if (err) alert("Cal omplir totes les caselles!");

            return err ? false : true;
        }

        function validateCalc() {

            var err = false;

            if (document.forms["prodAdd"]["c21"].value == "0") {
            
                if (document.forms["frecalc"]["calcFactor"].value == "0") err = true;
                if (err) alert("Cal omplir el factor de calcul!");
            }
            else {

                var dividend = document.forms["prodAdd"]["c21"].value;
                var divisor = document.forms["prodAdd"]["c11"].value;
                document.forms["frecalc"]["calcFactor"].value = dividend / divisor;
            }

            return err ? false : true;
        }

        function recalcula() {

            var fact = document.forms["frecalc"]["calcFactor"].value;

            var c12 = document.forms["prodAdd"]["c12"].value * fact;
            var c13 = document.forms["prodAdd"]["c13"].value * fact;
            var c14 = document.forms["prodAdd"]["c14"].value * fact;
            var c15 = document.forms["prodAdd"]["c15"].value * fact;
            var c16 = document.forms["prodAdd"]["c16"].value * fact;
            var c17 = document.forms["prodAdd"]["c17"].value * fact;
            var c18 = document.forms["prodAdd"]["c18"].value * fact;
            var c19 = document.forms["prodAdd"]["c19"].value * fact;

            document.forms["prodAdd"]["c22"].value = Math.round(c12 * 100) / 100;
            document.forms["prodAdd"]["c23"].value = Math.round(c13 * 100) / 100;
            document.forms["prodAdd"]["c24"].value = Math.round(c14 * 100) / 100;
            document.forms["prodAdd"]["c25"].value = Math.round(c15 * 100) / 100;
            document.forms["prodAdd"]["c26"].value = Math.round(c16 * 100) / 100;
            document.forms["prodAdd"]["c27"].value = Math.round(c17 * 100) / 100;
            document.forms["prodAdd"]["c28"].value = Math.round(c18 * 100) / 100;
            document.forms["prodAdd"]["c29"].value = Math.round(c19 * 100) / 100;
        }

        function recalsodia() {

            var c19 = document.forms["prodAdd"]["c19"];
            var sod = document.forms["sodium"]["calcSodi"].value;

            c19.value = (sod * <?php echo SAL_SODI?>) / <?php echo MILIGRAMS?>;
        }
</script>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable ="";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php"; 
    include "./includes/menu.inc.php"; 
    $menuType = MENU_PRODUCT;
    include "./includes/dietMenu.inc.php";
?>
<h3>Nou producte</h3>
<form id="prodAdd" method="post" action="xDietProduct_2.php" onsubmit="return validateForm()">

    <table>
    <tr><td>Nom</td>
        <td><input type="text" id="prName" name="prName" value="" onchange="ListRelated()"></td></tr>
    <tr><td>Categoria</td>
        <td><select id="prCat" name="prCat">
        <?php 
        $sql = "select distinct(food) from diet_products";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {

            echo "<option value='".$row['food']."'>".$row['food']."</option>";
        }
?>
        </select></td></tr>
    <tr><td>Descripció</td>
        <td><textarea type="text" id="prDesc" name="prDesc" value="" cols="70" rows="3"></textarea></td></tr>
    <tr><td>Descripció curta</td>
        <td><input type="text" id="prShort" name="prShort" value="" size="60"></td></tr>
    <tr><td>Ingredients</td>
        <td><textarea type="text" id="prComp" name="prComp" value="" cols="70" rows="3"></textarea></td></td></tr>
    <tr><td>Marca</td>
        <td><select id="prBran" name="prBran">
<?php 
        $sql = "select * from diet_product_brands order by name";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {

            echo "<option value='".$row['IDbrand']."'>".$row['name']."</option>";
        }
?>
        </select></td></tr>
    <tr><td>Tipus</td>
        <td><select id="prProc" name="prProc">
            <option value="No processat">No processat</option>
            <option value="Processat">Processat</option>
            <option value="Ultraprocessat">Ultraprocessat</option>
        </select></td></tr>
    <tr><td>Estat</td>
        <td><select id="prStat" name="prStat">
            <option value="A">Actiu</option>
            <option value="I">Inactiu</option>
        </select></td></tr>
    </table>
    <hr>
    <table>
        <tr><td></td><td>per 100g/ml</td><td></td><td>per ració</td></tr>
        <tr><td>Grams</td>
            <td><input class="number-input" type="number" value="100" id="c11" name="c11" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c21" name="c21" step="any"></td><td>g</td>
        </tr>
        <tr><td>Energia</td>
            <td><input class="number-input" type="number" value="0" id="c12" name="c12" step="any"></td><td>Kcal</td>
            <td><input class="number-input" type="number" value="0" id="c22" name="c22" step="any"></td><td>Kcal</td>
        </tr>
        <tr><td>Greixos</td>
            <td><input class="number-input" type="number" value="0" id="c13" name="c13" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c23" name="c23" step="any"></td><td>g</td>
        </tr>
        <tr><td>>Saturats</td>
            <td><input class="number-input" type="number" value="0" id="c14" name="c14" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c24" name="c24" step="any"></td><td>g</td>
        </tr>
        <tr><td>Carbohidrats</td>
            <td><input class="number-input" type="number" value="0" id="c15" name="c15" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c25" name="c25" step="any"></td><td>g</td>
        </tr>
        <tr><td>>Sucres</td>
            <td><input class="number-input" type="number" value="0" id="c16" name="c16" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c26" name="c26" step="any"></td><td>g</td>
        </tr>
        <tr><td>Fibra</td>
            <td><input class="number-input" type="number" value="0" id="c17" name="c17" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c27" name="c27" step="any"></td><td>g</td>
        </tr>
        <tr><td>Proteïna</td>
            <td><input class="number-input" type="number" value="0" id="c18" name="c18" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c28" name="c28" step="any"></td><td>g</td>
        </tr>
        <tr><td>Sal</td>
            <td><input class="number-input" type="number" value="0" id="c19" name="c19" step="any"></td><td>g</td>
            <td><input class="number-input" type="number" value="0" id="c29" name="c29" step="any"></td><td>g</td>
        </tr>
    </table>
    <input type="submit" value="Envia"> 
</form>

<div id="recalc" class='textCard expl'>
    <input type="button" value="Modifica un producte" onclick="location.href='xDietProduct_3.php'"> 
    <br><br><hr>
    <form id="frecalc" onsubmit="return false;">
        Càlcul de la ració amb multiplicador<br>
        <label for="calcFactor">Factor</label>
        <input class="number-input" step="any" value="0" id="calcFactor" name="calcFactor" step="any">
        <br>
        <input type="button" onclick="javascript:if (validateCalc()) recalcula()" value="Extrapola valors">
    </form>
    <br><hr>
    <form id="sodium" onsubmit="return false;">
        Càlcul de Sal a partir de sodi<br>
        <label for="calcFactor">Sodi (mg)</label> 
        <input class="number-input" step="any" value="0" id="calcSodi" name="calcSodi" step="any">
        <br>
        <input type="button" onclick="javascript:recalsodia()" value="Calcula la sal">
    </form>
</div>

<?php
include './includes/googleFooter.inc.php';
?>