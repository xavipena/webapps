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
            background: linear-gradient(to left, transparent , #1f1f1f 45%), url("./images/pexels-scottwebb-28054.jpg");
            background-size: cover;
        }
        .PGCRuler {
            display: flex;
            width: 900px;
        }
        td {
            vertical-align: middle;
        }
    </style>
    <script>
        function updateUser() {

            var pgc = document.getElementById("myPGC").value
            console.log("PGC:"+pgc);
            if (pgc == 0) 
            {
                alert("<?php echo locale("strMsg_1")?>");
                return false;
            }
            location.href="xDietPGC_1.php?pgc=" + pgc;
        }

        function validateForm() {

            var err = false;

            if (getQuantity("weight","dietPGC") == 0) err = 1;
            else if (getQuantity("height","dietPGC") == 0) err = 1;
            else if (getQuantity("age","dietPGC") == 0) err = 1;

            if (err) alert("<?php echo locale("strMsg_2")?>");

            return err ? false : true;
        }

        function getGender() {

            var gen = "";
            var theForm = document.forms["dietPGC"];
            var selectedGen = theForm.elements["gender"].value;
            console.log("Gen:"+gen);
            return gen;
        }

        function getQuantity(item, frm)
        {
            if (item == null) return;

            var theForm = document.forms[frm];
            var quantity = theForm.elements[item];
            var qty = 0;
            if (quantity.value != "")
            {
                qty = parseFloat(quantity.value).toFixed(2);
            }
            return qty;
        }

        function getPGC() {

            var wgt = getQuantity("weight","dietPGC");
            var hei = getQuantity("height","dietPGC");
            var age = getQuantity("age","dietPGC");
            var gen = getGender() == "H" ? 1 : 2;
            
            var res = document.getElementById('PGCResult');
            var IMC = CalcBMI(hei, wgt);
            PGC = (IMC * 1.2) + (age * 0.23) - (10.8 * gen) - 5.4;
            PGC = Math.round(PGC * 100) / 100;
            res.innerHTML = "<br>PGC = " + PGC + "%";
            res.innerHTML += "<br>" + getMessagePGC(getGender(), age, PGC) + "<br>";

            // Save for latter user
            document.getElementById("myPGC").value = PGC;

            var ptr = document.getElementById('PGCPointer');
            let PGC2 = PGC * 2;
            ptr.style = "text-align: right; font-size: x-large; width: " + PGC2 + "%;";

            var obj = document.getElementById('prInfo');
            obj.style.display = "block";
        }

        function SavePGC() {

            var pgc = document.getElementById("myPGC").value
            location.href = 'xDietPGC_1.php?pgc=' + pgc;
        }
    </script>
</head>
<body>
<?php 
/* 
    fórmula de Deurenberg:
    % Grasa corporal = (1.20 x IMC) + (0.23 x Edad) – (10.8 x Genero*) – 5.4.

    *Siendo Género: Hombre = 1, Mujer = 2.
    https://pubmed.ncbi.nlm.nih.gov/2043597/
*/
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 51;
    include "./includes/dietMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<div class='container'>";
?>
<form id="dietPGC" method="" action="" onsubmit="return false">
    
    <h2><?php echo locale("strTitle_6")?></h2>
    <div class='round'><?php echo locale("strTitle_7")?></div><br>

    <input type="hidden" id="dtUser" name="dtUser" value="1">
    <input type="hidden" id="dtCals" name="dtCals" value="0">

    <table>
        <tr>
            <td><?php echo locale("strAge")?></td>
            <td>
                <input class='number-input' type="number" id="age" name="age" value="0" required>
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
                <input class="number-input" type="number" step="any" id="weight" name="weight" value="0"/>
            </td>
        </tr>
        <tr>
            <td><?php echo locale("strHeight")?> (cm)</td>
            <td>
                <input class="number-input" type="number" step="any" id="height" name="height" value="0"/>
            </td>
        </tr>
    </table>
    <input type="button" value=" <?php echo locale("strCompute")?> " onclick="javascript:if (validateForm()) getPGC();">
    <?php
        $gender = "H";
        $age = 30;
        if (isset($_SESSION['diet_user'])) {

            if ($_SESSION['diet_user'] != "") {

                $sql = "select gender from diet_users where IDuser = ".$_SESSION['diet_user'];
                $result = mysqli_query($db, $sql);
                $gender = mysqli_fetch_array($result)[0];

                echo "<script>";
                echo "document.getElementById('gender').value = '$gender';";

                $sql = "select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
                $result = mysqli_query($db, $sql);
                if ($row = mysqli_fetch_array($result)) {

                    echo "document.getElementById('age').value = ".$row['age'].";";
                    echo "document.getElementById('weight').value = ".$row['weight'].";";
                    echo "document.getElementById('height').value = ".$row['height'].";";
                }
                echo "</script>";
            }
        }
        // double the values over 50 to get percent over 100 
        if ($gender == "D") {

            if ($age <= 39) {
    
                $seg_1 = 16;
                $seg_2 = 28;
                $seg_3 = 39;
                $seg_4 = 50;
            }
            else if ($age <= 59) {
    
                $seg_1 = 18;
                $seg_2 = 30;
                $seg_3 = 40;
                $seg_4 = 50;
            }
            else if ($age <= 79) {
    
                $seg_1 = 20;
                $seg_2 = 32;
                $seg_3 = 42;
                $seg_4 = 50;
            }
        }
        else {
    
            if ($age <= 39) {
    
                $seg_1 = 8;
                $seg_2 = 20;
                $seg_3 = 25;
                $seg_4 = 50;
            }
            else if ($age <= 59) {
    
                $seg_1 = 11;
                $seg_2 = 22;
                $seg_3 = 28;
                $seg_4 = 50;
            }
            else if ($age <= 79) {
    
                $seg_1 = 13;
                $seg_2 = 25;
                $seg_3 = 30;
                $seg_4 = 50;
            }
        }
    ?>
    <div style="display:none" id="prInfo">
        <div id="PGCResult"></div>
        <div class='PGCRuler'>
            <div id='PGCPointer'>↓</div>
        </div>
        <div class='PGCRuler'>
            <div style='width:<?php echo $seg_1 * 2?>%; text-align: center; background-color: #1c4d5c;'><?php echo locale("strPGC_1")?></div>
            <div style='width:<?php echo ($seg_2 - $seg_1) * 2?>%; text-align: center; background-color: #2a7512;'><?php echo locale("strPGC_2")?></div>
            <div style='width:<?php echo ($seg_3 - $seg_2) * 2?>%; text-align: center; background-color: #a1a100;'><?php echo locale("strPGC_3")?></div>
            <div style='width:<?php echo ($seg_4 - $seg_3) * 2?>%; text-align: center; background-color: #f39c12;'><?php echo locale("strPGC_4")?></div>
        </div>
        <div class='PGCRuler'>
            <div style='width:<?php echo $seg_1 * 2?>%; text-align: right;'><?php echo $seg_1?></div>
            <div style='width:<?php echo ($seg_2 - $seg_1) * 2?>%; text-align: right;'><?php echo $seg_2?></div>
            <div style='width:<?php echo ($seg_3 - $seg_2) * 2?>%; text-align: right;'><?php echo $seg_3?></div>
            <div style='width:<?php echo ($seg_4 - $seg_3) * 2?>%; text-align: right;'><?php echo $seg_4?></div>
        </div>
        <input type="hidden" id="myPGC" value="0">
        <input type="button" value=" <?php echo locale("strUpdUser")?> " onclick="javascript:updateUser()">
    </div>
</form>

<?php
    $glossary_PGC = 31;
    $sql = "select * from diet_glossary where IDterm = $glossary_PGC";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
    }
    echo "</div>";
    


    include './includes/googleFooter.inc.php';
?>