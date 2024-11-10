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

    /*
    Interpretación del IMLG en mujeres

    13-14 = bajo
    15-16 = normal
    17-18 = bueno
    19-20 = muy bueno
    22 = límite máximo

    
    Interpretación del IMLG en hombres

    17-18 = bajo
    19-20 = promedio
    21-22 = bueno
    23-24 = muy bueno
    25 = límite muscular máximo
*/
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 45%), url("./images/pexels-scottwebb-28054.jpg");
            background-size: cover;
        }
        .IMLGRuler {
            display: flex;
            width: 900px;
        }
        td {
            vertical-align: middle;
        }
    </style>
    <script>
        function updateUser() {

            var IMLG = document.getElementById("myIMLG").value
            console.log("IMLG:"+IMLG);
            if (IMLG == 0) 
            {
                alert("<?php echo locale("strMsg_1")?>");
                return false;
            }
            location.href="xDietIMLG_1.php?IMLG=" + IMLG;
        }

        function validateForm() {

            var err = false;
            var form = "dietIMLG";

            if (getQuantity("weight",form) == 0) err = 1;
            else if (getQuantity("height",form) == 0) err = 1;

            if (err) alert("<?php echo locale("strMsg_2")?>");

            return err ? false : true;
        }

        function getGender() {

            var gen = "";
            var theForm = document.forms["dietIMLG"];
            var selectedGen = theForm.elements["gender"].value;
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

        function getIMLG() {

            var wgt = getQuantity("weight","dietIMLG");
            var hei = getQuantity("height","dietIMLG");
            var gen = getGender() == "H" ? 1 : 2;
            var pgc = getQuantity("PGC","dietIMLG");
            
            var res = document.getElementById('IMLGResult');

            var MLG = wgt * (100 - pgc) / 100;
            var height = hei / 100;
            //var IMLG = Math.round(MLG / (height * height) + (6.3 + (1.8 - height)));
            var IMLG = Math.round(MLG / (height * height));

            res.innerHTML = "<br>IMLG = " + IMLG;
            res.innerHTML += "<br>" + getMessageIMLG(getGender(), IMLG) + "<br>";

            // Save for latter user
            document.getElementById("myIMLG").value = IMLG;

            var ptr = document.getElementById('IMLGPointer');
            let IMLG2 = IMLG * 2;
            ptr.style = "text-align: right; font-size: x-large; width: " + IMLG2 + "%;";

            var obj = document.getElementById('prInfo');
            obj.style.display = "block";
        }

        function SaveIMLG() {

            var IMLG = document.getElementById("myIMLG").value
            location.href = 'xDietIMLG_1.php?IMLG=' + IMLG;
        }
    </script>
</head>
<body>
<?php 
/* 
    Massa lliure de greix, cal el PGC
    MLG = KG * (100 – PGC) / 100

    El Índice de Masa Libre de Grasa es la masa libre de grasa en relación con el tamaño corporal más un pequeño factor de corrección (Kouri et al.): 
    IMLG = MLG / (altura (m)^2) + 6,3 x (1,8 – altura (m))
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
    $selectedMenuOption = 52;
    include "./includes/dietMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<div class='container'>";
?>
<form id="dietIMLG" method="" action="" onsubmit="return false">
    
    <h2><?php echo locale("strTitle_8")?></h2>
    <div class='round'><?php echo locale("strTitle_9")?></div><br>

    <input type="hidden" id="dtUser" name="dtUser" value="1">
    <input type="hidden" id="dtCals" name="dtCals" value="0">

    <table>
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
            <td ><?php echo locale("strHeight")?> (cm)</td>
            <td>
                <input class="number-input" type="number" step="any" id="height" name="height" value="0"/>
            </td>
        </tr>
        <tr>
            <td>PGC (%)</td>
            <td>
                <input class="number-input" type="number" step="any" id="PGC" name="PGC" value="0"/>
            </td>
        </tr>
    </table>
    <input type="button" value=" <?php echo locale("strCompute")?> " onclick="javascript:if (validateForm()) getIMLG();">
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

                    echo "document.getElementById('weight').value = ".$row['weight'].";";
                    echo "document.getElementById('height').value = ".$row['height'].";";
                    echo "document.getElementById('PGC').value = ".$row['PGC'].";";
                }
                echo "</script>";
            }
        }

        // souble the values over 50 to get percent over 100 
        if ($gender == "D") {

            $seg_1 = 14;
            $seg_2 = 16;
            $seg_3 = 18;
            $seg_4 = 22;
            $seg_5 = 22;
        }
        else {
            $seg_1 = 18;
            $seg_2 = 20;
            $seg_3 = 22;
            $seg_4 = 24;
            $seg_5 = 25;
        }
    ?>
    <div style="display:none" id="prInfo">
        <div id="IMLGResult"></div>
        <div class='IMLGRuler'>
            <div id='IMLGPointer'>↓</div>
        </div>
        <div class='IMLGRuler'>
            <div style='width:<?php echo $seg_1 * 2?>%; text-align: center; background-color: #1c4d5c;'>A</div>
            <div style='width:<?php echo ($seg_2 - $seg_1) * 2?>%; text-align: center; background-color: #2a7512;'>B</div>
            <div style='width:<?php echo ($seg_3 - $seg_2) * 2?>%; text-align: center; background-color: #a1a100;'>C</div>
            <div style='width:<?php echo ($seg_4 - $seg_3) * 2?>%; text-align: center; background-color: #f39c12;'>D</div>
            <div style='width:<?php echo ($seg_5 - $seg_4) * 2?>%; text-align: center; background-color: #e67e22;'>E</div>
        </div>
        <div class='IMLGRuler'>
            <div style='width:<?php echo $seg_1 * 2?>%; text-align: right;'><?php echo $seg_1?></div>
            <div style='width:<?php echo ($seg_2 - $seg_1) * 2?>%; text-align: right;'><?php echo $seg_2?></div>
            <div style='width:<?php echo ($seg_3 - $seg_2) * 2?>%; text-align: right;'><?php echo $seg_3?></div>
            <div style='width:<?php echo ($seg_4 - $seg_3) * 2?>%; text-align: right;'><?php echo $seg_4?></div>
            <div style='width:<?php echo ($seg_5 - $seg_4) * 2?>%; text-align: right;'><?php echo $seg_5?></div>
        </div>
        <input type="hidden" id="myIMLG" value="0">
        <ul>
            <li>A - <?php echo locale("strIMLG_1")?></li>
            <li>B - <?php echo locale("strIMLG_2")?></li>
            <li>C - <?php echo locale("strIMLG_3")?></li>
            <li>D - <?php echo locale("strIMLG_4")?></li>
            <li>E - <?php echo locale("strIMLG_5")?></li>
        </ul>
        <input type="button" value=" <?php echo locale("strUpdUser")?> " onclick="javascript:updateUser()">
    </div>
</form>

<?php
    echo "<div class='vcontainer'>";
    $glossary_IMLG = "(32,33)";
    $sql = "select * from diet_glossary where IDterm in $glossary_IMLG";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
    }
    echo "</div>";
    echo "</div>";
    
    include './includes/googleFooter.inc.php';
?>