<?php
/*
    UPDATE
 */
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include './includes/settingsStart.inc.php';
    include "./includes/sideMenuHover_1.inc.php";
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
        function ToReadonly(status)
        {
            var inputs = document.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; ++i) {

                inputs[i].readOnly = status;
            }
            inputs = document.getElementsByTagName('select');
            for (var i = 0; i < inputs.length; ++i) {

                inputs[i].readOnly = status;
            }
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
</script>
</head>
<body>
<?php 
    //--- Params ------------------------- 

    $pID = empty($clean['dtProd']) ? "" : $clean['dtProd'];

    //--- Settings ----------------------- 

    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $page ="Diet";
    $sourceTable ="diet_products";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions çç-------------------- 

    include "./includes/dietFunctions.inc.php";

    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php"; 
    include "./includes/menu.inc.php"; 
    $menuType = MENU_PRODUCT;
    include "./includes/dietMenu.inc.php";
?>
<h3>Modifica producte</h3>
<?php 
    if (empty($pID)) { ?>

        <form id="pID" method="post" action="xDietProduct_3.php">
            <select id="dtProd" name="dtProd">
            <option value="">Selecciona producte</option>
<?php 
            $sql = "select * from diet_products where status = 'A' order by name";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) {

                echo "<option value='".$row['IDproduct']."'>".$row['name']."</option>";
            }
?>
            </select>
            <input type="submit" value="Envia"> 
        </form>
<?php
        include './includes/googleFooter.inc.php';
        exit;
    }
    echo "<img class='product' src='".GetImage("diet", $pID)."' width='150px'>";
?>
<form id="prodAdd" method="post" action="xDietProduct_4.php" onsubmit="return validateForm()">
    <input type="hidden" id="prID" name="prID" value="">
    <table>
    <tr><td>Nom</td>
        <td><input type="text" id="prName" name="prName" value=""></td></tr>
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
            <td><input class="number-input" type="number" value="0" id="c11" name="c11" step="any"></td><td>g</td>
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

<div id="recalc">
    <input type="button" value="Modifica" onclick="javascript:ToReadonly(false)"> 
    <form id="frecalc" onsubmit="return false;">
        Càlcul de la ració amb multiplicador<br>
        Factor <input type="number" size="6" step="any" value="0" id="calcFactor" name="calcFactor" step="any">
        <br>
        <input type="button" onclick="javascript:if (validateCalc()) recalcula()" value="Extrapola valors">
    </form>
</div>
<script>
<?php
    $sql = "select * from diet_products where IDproduct = $pID";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
?>
    document.getElementById("prID").value    = <?php echo $row['IDproduct']?>;
    document.getElementById("prName").value  = '<?php echo $row['name']?>';
    document.getElementById("prCat").value   = '<?php echo $row['food']?>';
    document.getElementById("prDesc").value  = '<?php echo $row['description']?>';
    document.getElementById("prShort").value = '<?php echo $row['short']?>';
    document.getElementById("prComp").value  = '<?php echo $row['ingredients']?>';
    document.getElementById("prBran").value  = <?php echo $row['brand']?>;
    document.getElementById("prProc").value  = '<?php echo $row['type']?>';
    document.getElementById("prStat").value  = '<?php echo $row['status']?>';
<?php
    $sql = "select * from diet_product_data where IDproduct = $pID and unit = 'Standard'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    echo "const p1data = [";
    for ($i = 2; $i <= 11; $i++) echo $row[$i].", ";
    echo "0];".PHP_EOL;
?>
    for (let i = 1; i <= 9; i++) {
    
        document.getElementById("c1" + i).value = p1data[i -1];
    }
<?php
    $sql = "select * from diet_product_data where IDproduct = $pID and unit = 'Ration'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    echo "const p2data = [";
    for ($i = 2; $i <= 11; $i++) echo $row[$i].", ";
    echo "0];".PHP_EOL;
?>
    for (let i = 1; i <= 9; i++) {
    
    document.getElementById("c2" + i).value = p2data[i -1];
    }
    ToReadonly(true);
</script>
<?php
include './includes/googleFooter.inc.php';
?>