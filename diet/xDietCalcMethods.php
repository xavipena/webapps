<?php
    session_start();
    $runner_id = "";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        td {
            vertical-align: middle;
        }        
    </style>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- params ---------------------- 

    $profile = empty($clean['profile']) ? "A" : $clean['profile'];

    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 4;
    include "./includes/dietMenu.inc.php";

    if (!isset($_SESSION['diet_user'])) 
    {
        echo "<a href='xDietUser_1.php'>".locale("strStartSession")."</a> ".locale("strText_6")."<br></br>";
    }
    echo "<div class='container'>";
?>

<form action="xDietUser_3.php" method="post" id="calccal" onsubmit="return false;">
    
    <h2><?php echo locale("strTitle_1")?></h2>
    <div class='round'><?php echo locale("strTitle_2")?></div><br>
    
    <input type="hidden" id="calProfile" name="calProfile" value="<?php echo $profile?>"/>
    <input type="hidden" id="calBasal" name="calBasal" value="0"/>
    <input type="hidden" id="calThermo" name="calThermo" value="0"/>
    <input type="hidden" id="calAdjust" name="calAdjust" value="0"/>
    <input type="hidden" id="calRecom" name="calRecom" value="0"/>
    <input type="hidden" id="calLimit" name="calLimit" value="0"/>

    <table width="400px">
        <tr>
            <td><?php echo locale("strTarget")?></td>
            <td>
                <select id="target" name="target" required>
                    <option value="A"><?php echo locale("strText_1")?></option>
                    <option value="B"><?php echo locale("strText_2")?></option>
                    <option value="C"><?php echo locale("strText_3")?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo locale("strGender")?></td>
            <td>
                <select id="gender" name="gender" required>
                    <option value="H"><?php echo locale("strGender_M")?></option>
                    <option value="D"><?php echo locale("strGender_F")?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo locale("strWeight")?> (kg)</td>
            <td>
                <input class='number-input' type="number" id="weight" name="weight" value="0" required>
            </td>
        </tr>
        <tr>
            <td><?php echo locale("strHeight")?> (cm)</td>
            <td>
                <input class='number-input' type="number" id="height" name="height" value="0" required>
            </td>
        </tr>
        <tr>
            <td><?php echo locale("strAge")?></td>
            <td>
                <input class='number-input' type="number" id="age" name="age" value="0" required>
            </td>
        </tr>
    </table>
    <fieldset>
        <legend><?php echo locale("strActivity")?>:</legend>
        <input type="radio" name="activity" id="actA" value="A" /><?php echo locale("strActivity_1")?><br>
        <input type="radio" name="activity" id="actB" value="B" /><?php echo locale("strActivity_2")?><br>
        <input type="radio" name="activity" id="actC" value="C" /><?php echo locale("strActivity_3")?><br>
        <input type="radio" name="activity" id="actD" value="D" /><?php echo locale("strActivity_4")?><br>
        <input type="radio" name="activity" id="actE" value="E" /><?php echo locale("strActivity_5")?><br>
    </fieldset>
    <br>
    <?php echo locale("strActivity_6")?>
    <br><br>
<?php
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
?>            
            <input type="button" value="<?php echo locale("strUpdUser")?>" onclick="javascript:if (validate()) updateUser('calccal')">
<?php
        }
    }
?>
    <input type="button" value="<?php echo locale("strCompute")?>" onclick="javascript:if (validate()) getCalories()">
<?php
    $PGC = 0;
    if ($profile == "B" || $profile == "C") {

        $sql = "select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) {

            $PGC = $row['PGC'];
        }
        if ($PGC == 0) {
    
            echo "<br><br><span style='color:red;'>Falta calcular el teu PGC. Desa'l per poder utilitzar aquest mètode</span>";
        }
    }
?>
    <br><br>
    <div id="totalBasal"></div>
</form>
<br>
<?php
/*
    Ecuación de Harris Benedict.
    ----------------------------
    Hombres: TMB = (10 x peso en kg) + (6.25 x altura en cm) – (5 x edad en años) + 5
    Mujeres: TMB = (10 x peso en kg) + (6.25 x altura en cm) – (5 x edad en años) – 161

    Ecuación de Katch – McArdle, revisada por Cunningham et al.
    ----------------------------
    Mayor utilidad y capacidad en determinados grupos de población como los deportistas. Para hombre sy mujeres

    Gasto energético diario = FA x (370 + 21.6 x Masa Libre de Grasa (kg))

    Factor de actividad (FA)
    Sedentario (poco o ningún ejercicio): FA = 1.2
    Actividad ligera (ejercicio ligero o deporte 1-3 días a la semana): FA = 1.375
    Actividad moderada (ejercicio moderado o deporte 3-5 días a la semana): FA = 1.55
    Actividad intensa (ejercicio intenso o deporte 6-7 días a la semana): FA = 1.725
    Actividad muy intensa (ejercicio muy intenso o trabajo físico y ejercicio diario): FA = 1.9

    Tinsley et al.
    ----------------------------
    La calculadora de Tinsley supone una alternativa más ajustada a personas, hombres y mujeres, que entrenan habitualmente y tienen un buen desarrollo muscular.

    Gasto energético diario = FA x (284 + 25.9 x Masa Libre de Grasa (kg))

    Sedentario (poco o ningún ejercicio): FA = 1.2
    Actividad ligera (ejercicio ligero o deporte 1-3 días a la semana): FA = 1.375
    Actividad moderada (ejercicio moderado o deporte 3-5 días a la semana): FA = 1.55
    Actividad intensa (ejercicio intenso o deporte 6-7 días a la semana): FA = 1.725
    Actividad muy intensa (ejercicio muy intenso o trabajo físico y ejercicio diario): FA = 1.9
 */
?>
    <script>      
        var PGC = <?php echo $PGC?>;
        debugit = false;

        const for_B = 370;
        const for_C = 284;
        const woman_0 = -161;
        const man_0 = 5;

        const weight_0 = 10;
        const height_0 = 6.25;
        const age_0 = 5;

        var activityID = new Array();
        activityID["A"] = 1.2;
        activityID["B"] = 1.375;
        activityID["C"] = 1.55;
        activityID["D"] = 1.725;
        activityID["E"] = 1.9;

        var calories = 0;

        function updateUser(frm) {

            if (document.getElementById('calBasal').value == 0) 
            {
                alert("<?php echo locale("strMsg_1")?>");
                return false;
            }
            document.forms[frm].submit();
        }

        function getActivity() {

            var act = 0;
            var theForm = document.forms["calccal"];
            var selectedAct = theForm.elements["activity"];

            for (var i = 0; i < selectedAct.length; i++)
            {
                if (selectedAct[i].checked)
                {
                    act = activityID[selectedAct[i].value];
                    break;
                }
            }
            return act;
        }

        function getLossDietType() {

            var act = 0;
            var theForm = document.forms["calcLoss"];
            var selectedAct = theForm.elements["lossDiet"];

            for (var i = 0; i < selectedAct.length; i++)
            {
                if (selectedAct[i].checked)
                {
                    act = selectedAct[i].value;
                    break;
                }
            }
            return act * 1;
        }

        function getQuantity(item, frm) {

            if (item == null) return;

            var theForm = document.forms[frm];
            var quantity = theForm.elements[item];
            var qty = 0;
            if (quantity.value != "")
            {
                qty = parseInt(quantity.value);
            }
            return qty;
        }

        function getWeight(factor) {

            return getQuantity("weight","calccal") * factor;
        }

        function getHeight(factor) {

            return getQuantity("height","calccal") * factor;
        }

        function getAge(factor) {

            return getQuantity("age","calccal") * factor;
        }

        function validate() {

            var err = 0;
            if (getQuantity("weight","calccal") == 0) err = 1;
            else if (getQuantity("height","calccal") == 0) err = 1;
            else if (getQuantity("age","calccal") == 0) err = 1;
            else if (getActivity() == 0) err = 1;
            if (err) 
            {
                alert("<?php echo locale("strMsg_2")?>");
                return false;
            }
            return true;
        }

        function validateWeightLoss() {

            var err = 0;
            if (getQuantity("lossKg","calcLoss") == 0) err = 1;
            else if (getQuantity("lossWeeks","calcLoss") == 0) err = 1;
            else if (getLossDietType() == 0) err = 1;

            if (err) 
            {
                alert("<?php echo locale("strMsg_2")?>");
                return false;
            }
            return true;
        }

        function validateDiff() {

            var err = 0;
            if (getQuantity("cals","calcdif") == 0) err = 1;

            if (err) 
            {
                alert("<?php echo locale("strMsg_2")?>");
                return false;
            }

            if (document.getElementById('calBasal').value == 0) 
            {
                alert("P<?php echo locale("strMsg_1")?>");
                return false;
            }
            return true;
        }

        function addRow(table, col1, col2, unit, desc) {

            var tr = document.createElement('tr');
            
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            td1.width = "25%";
            td2.width = "20%";
            td2.className = "number";
            
            var text1 = document.createTextNode(col1);
            var text2 = document.createTextNode(col2 + " " + unit);
            var text3 = document.createTextNode(desc);
            
            td1.appendChild(text1);
            td2.appendChild(text2);
            td3.appendChild(text3);
            
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);

            table.appendChild(tr);
        }
        
        function getTarget() {

            var tar = "";
            var theForm = document.forms["calccal"];
            var selectedTar = theForm.elements["target"];
            return selectedTar.value;
        }

        function getCalories() {

            <?php 
            switch ($profile) {
                case "A":
                    echo "getCaloriesPrA();";
                    break;
                case "B":
                    echo "getCaloriesPrB();";
                    break;
                case "C":
                    echo "getCaloriesPrC();";
                    break;
            } 
            ?>
        }

        <?php if ($profile == "A") { ?>

            function getCaloriesPrA() {

            resDiv = document.getElementById("totalBasal");
            resDiv.innerHTML = "";

            var g = document.forms["calccal"].elements["gender"].value;
            if (g == "D") 
            {
                calories = (woman_0 + getWeight(weight_0) + getHeight(height_0) - getAge(age_0));
            }
            else 
            {
                calories = (man_0 + getWeight(weight_0) + getHeight(height_0) - getAge(age_0));
            }

            var table = document.createElement('table');
            table.className = 'dietcard';
            table.width = "600px";

            <?php 
                $sql = "select * from diet_glossary where IDterm = 1";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>
            addRow(table, "<?php echo locale("strCalBasal")?>", Math.round(calories), 'Kcal', desc);
            document.getElementById('calBasal').value = Math.round(calories);

            thermogenesis = calories * 0.1;
            <?php 
                $sql = "select * from diet_glossary where IDterm = 2";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>
            addRow(table, '<?php echo locale("strCalTermogen")?>', Math.round(thermogenesis), 'Kcal', desc);
            document.getElementById('calThermo').value = Math.round(thermogenesis);
            //calories += thermogenesis;
            
            var adjust = calories * getActivity();
            calories = adjust; 

            <?php 
                $sql = "select * from diet_glossary where IDterm = 3";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>
            addRow(table, '<?php echo locale("strCalActivity")?>', Math.round(adjust), 'Kcal', desc);

            <?php 
                $sql = "select * from diet_glossary where IDterm = 30";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>
            addRow(table, '<?php echo locale("strCalRecommend")?>', Math.round(calories), 'Kcal', desc);
            document.getElementById('calAdjust').value = Math.round(adjust);
            document.getElementById('calRecom').value = Math.round(calories);

            resDiv.appendChild(table);
        }
        <?php } 
        if ($profile == "B") { ?>

        function getCaloriesPrB() {

            if (PGC == 0) {
                alert("Calcula el teu PGC i desa'l per poder utilitzar aquest mètode");
                return;
            }
            var table = document.createElement('table');
            table.className = 'dietcard';
            table.width = "600px";
        
            <?php 
            // Gasto energético diario = FA x (370 + 21.6 x Masa Libre de Grasa (kg)) 
            // MLG = KG * (100 – PGC) / 100
            // IMLG = MLG / (altura (m) ^2) + 6,3 x (1,8 – altura (m))
            ?>
            var weight = getWeight(1);
            var height = getHeight(1);
            var MLG = Math.round(weight * (100 - PGC) / 100, 2);
            var meters = height / 100;
            //var IMLG = Math.round(MLG / (meters * meters) + 6.3 * (1.80 - meters));
            var act = getActivity();
            var calories = 370 + (21.6 * MLG);
            document.getElementById('calBasal').value = Math.round(calories);
            calories = Math.round(act * calories, 0);
            document.getElementById('calRecom').value = Math.round(calories);

            <?php 
                $sql = "select * from diet_glossary where IDterm = 31";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>

            addRow(table, "Porcentaje de grasa corporal", PGC, "%", desc);

            <?php 
                $sql = "select * from diet_glossary where IDterm = 33";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>

            addRow(table, "Masa libre de grasa", MLG, "%", desc);
            //addRow(table, "Índice de masa libre de grasa", IMLG, "", "");
            addRow(table, 'Factor per activitat', act, "", "");

            <?php 
                $sql = "select * from diet_glossary where IDterm = 30";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>
            addRow(table, 'Calories recomanades', calories, "Kcal", desc);

            resDiv = document.getElementById("totalBasal");
            resDiv.innerHTML = "";
            resDiv.appendChild(table);
        }
        <?php } 
        if ($profile == "C") { ?>
        
        function getCaloriesPrC() {

            if (PGC == 0) {
                alert("Calcula el teu PGC i desa'l per poder utilitzar aquest mètode");
                return;
            }
            var table = document.createElement('table');
            table.className = 'dietcard';
            table.width = "600px";
            
            <?php // Gasto energético diario = FA x (284 + 25.9 x Masa Libre de Grasa (kg)) ?>
            var weight = getWeight(1);
            var height = getHeight(1);
            var MLG = Math.round(weight * (100 - PGC) / 100, 2);
            var act = getActivity();
            var calories = 284 + (25.9 * MLG);
            document.getElementById('calBasal').value = Math.round(calories);
            calories = act * calories;
            document.getElementById('calRecom').value = Math.round(calories);

            <?php 
                $sql = "select * from diet_glossary where IDterm = 31";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>

            addRow(table, "Porcentaje de grasa corporal", PGC, "%", "");

            <?php 
                $sql = "select * from diet_glossary where IDterm = 33";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>

            addRow(table, "Masa libre de grasa", MLG, "%", "");
            addRow(table, 'Factor per activitat', act, "", "");
            
            <?php 
                $sql = "select * from diet_glossary where IDterm = 30";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                echo "var desc = '".$row['inShort']."';";
            ?>

            addRow(table, 'Calories recomanades', calories, "Kcal", desc);

            resDiv = document.getElementById("totalBasal");
            resDiv.innerHTML = "";
            resDiv.appendChild(table);
        }
        <?php } ?>

        function getLossCalories() {

            if (calories == 0) 
            {
                alert("<?php echo locale("strMsg_1")?>s");
                return;
            }
            let rowStyle = "style='text-align:right'";
            let weeks = document.getElementById("lossWeeks").value;
            let calPerKg = 7700;
            let calToLose = document.getElementById("lossKg").value * calPerKg;
            let calPerWeek = Math.round(calToLose / weeks);
            let calPerDay = Math.round(calPerWeek / 7);

            var table = document.createElement('table');
            table.className = 'dietcard';
            table.width = "400px";

            resDiv = document.getElementById("lossResult");

            addRow(table, locale("strMsg_3"), calPerDay, 'Kcal');
            addRow(table, locale("strMsg_4"), weeks * 7, locale("strDays"));

            var limitPerDay = getLossDietType();
            let tolerance = limitPerDay * 0.1;
            limitPerDay += tolerance
            if (calPerDay > limitPerDay) 
            {
                addRow(table, locale("strMsg_5"), limitPerDay, 'Kcal');
            }
            else 
            {
                if (Math.abs(limitPerDay - calPerDay) > 50) 
                {
                    var newLimit = Math.round(calories - calPerDay);
                    addRow(table, locale("strMsg_6"), newLimit, 'Kcal');
                    // recalcua dies amb la diferència
                    var newKg = (limitPerDay * 7 * weeks) / 7700;
                    addRow(table, locale("strMsg_7"), newKg, 'kg');
                    var newDays = Math.round(calToLose / limitPerDay);
                    var newWeeks = Math.round(newDays / 7);
                    addRow(table, locale("strMsg_8"), newDays, locale("strDays"));

                    // update form fields
                    document.getElementById("lossPerDay").value = calPerDay;
                    document.getElementById("lossLimit").value = newLimit;
                }
            }
            resDiv.appendChild(table);
        }

<?php
    $PGC = 0;
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {

            $sql = "select * from diet_users where IDuser = ".$_SESSION['diet_user'];
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) {
                
                echo "document.getElementById('gender').value = '".$row['gender']."';";
            }
            $sql = "select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) {

                echo "document.getElementById('target').value = '".$row['target']."';";
                if ($profile == "B" || $profile == "C") {

                    if (!empty($row['activity'])) {
                    
                        echo "document.getElementById('act".$row['activity']."').checked = true;";
                    }
                }
                echo "document.getElementById('weight').value = ".$row['weight'].";";
                echo "document.getElementById('height').value = ".$row['height'].";";
                echo "document.getElementById('age').value = ".$row['age'].";";

                $PGC = $row['PGC'];
            }
        }
    }
?>
</script>

<?php
    // cards in vertical list
    echo "<div class='vcontainer'>";
        $glossary_TMB = 29;
        $sql = "select * from diet_glossary where IDterm = $glossary_TMB";
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result))
        {
            //function AddCard($url, $prefix, $name, $description, $hasArrow = false, $loader = false, $isMenu = false) {
            AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
        }
        if ($PGC == 0) {

            AddCard("xDietPGC.php", "diet", locale("strPGC"), locale("strCardHelp_5"), true);
        }
        AddCard("xDietCalc_2.php", "diet", locale("strNext"), locale("strCardHelp_2"), true);
    echo "</div>";
    echo "</div>";
    
include './includes/googleFooter.inc.php';
?>