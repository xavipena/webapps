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
       td {
            vertical-align: middle;
        }
    </style>
    <script>
        function validateForm() {

            var err = false;

            if (getQuantity("weight","dietMET") == 0) err = 1;
            else if (getQuantity("height","dietMET") == 0) err = 1;
            else if (getQuantity("hours","dietMET") + getQuantity("minutes","dietMET") == 0) err = 1;

            if (err) alert("<?php echo locale("strMsg_2")?>");

            return err ? false : true;
        }

        function getQuantity(item, frm) {

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

        function CalcKcal(met, kg, min) {

            Kilos = eval(kg);
            Kalos = met * 0.0175 * Kilos * min;
            return Math.round(Kalos, 2);
        }

        function getMET() {

            var wgt = getQuantity("weight","dietMET");
            var met = getQuantity("activity","dietMET");
            var hh = Math.round(getQuantity("hours","dietMET"));
            var mm = Math.round(getQuantity("minutes","dietMET"));

            var obj = document.getElementById('prInfo');
            var mns = hh * 60 + mm;
            var met = CalcKcal(met, wgt, mns);
            obj.innerHTML = met + "Kcal";

            return false;
        }
    </script>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable ="";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 14;
    include "./includes/dietMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<div class='container'>";
?>

<form id="dietMET" method="" action="" onsubmit="return false">
    
    <h2><?php echo locale("strTitle_5")?></h2>
    <div class='round'><?php echo locale("strTitle_2")?></div><br>

    <input type="hidden" id="dtUser" name="dtUser" value="1">
    <input type="hidden" id="dtCals" name="dtCals" value="0">

    <table>
    <tr>
        <td><?php echo locale("strWeight")?> (kg)</td>
        <td>
            <input class='number-input' type="number" step="any" id="weight" name="weight" value="0"/>
        </td>
    </tr>
    <tr>
        <td><?php echo locale("strHeight")?> (cm)</td>
        <td>
            <input class='number-input' type="number" step="any" id="height" name="height" value="0"/>
        </td>
    </tr>
    <tr>
        <td>Activitat</td>
        <td>
            <select id="activity" name="activity">
<?php
    $sql = "select * from diet_activities order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        echo "<option value='".$row['met']."'>".$row['name']."</option>";
    }
?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php echo locale("strHours")?></td>
        <td>
            <input class='number-input' type="number" step="any" id="hours" name="hours" value="0"/>
        </td>
    </tr>
    <tr>
        <td><?php echo locale("strMinutes")?></td>
        <td>
            <input class='number-input' type="number" step="any" id="minutes" name="minutes" value="0"/>
        </td>
    </tr>
    </table>
    <input type="button" value="<?php echo locale("strCompute")?>" onclick="javascript:if (validateForm()) getMET();">
    <input type="button" value="<?php echo locale("strBack")?>" onclick="history.back()">
    <div class="roundedLink" id="prInfo"></div>
</form>

<?php
    $glossary_MET = 6;
    $sql = "select * from diet_glossary where IDterm = $glossary_MET";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
    }
    echo "</div>";

    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {

            $sql = "select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) {

                echo "<script>";
                echo "document.getElementById('weight').value = ".$row['weight'].";";
                echo "document.getElementById('height').value = ".$row['height'].";";
                echo "</script>";
            }
        }
    }

    include './includes/googleFooter.inc.php';
?>