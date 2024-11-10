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
        .imcRuler {
            display: flex;
            width: 900px;
        }
    </style>
    <script>
        function validateForm() {

            var err = false;

            if (getQuantity("weight","dietIMC") == 0) err = 1;
            else if (getQuantity("height","dietIMC") == 0) err = 1;

            if (err) alert("<?php echo locale("strMsg_2")?>");

            return err ? false : true;
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

        function getIMC() {

            var wgt = getQuantity("weight","dietIMC");
            var hei = getQuantity("height","dietIMC");
            
            var res = document.getElementById('imcResult');
            var imc = CalcBMI(hei, wgt);
            res.innerHTML = "<br>IMC = " + imc;
            res.innerHTML += "<br>" + getMessage(imc) + "<br>";

            var ptr = document.getElementById('imcPointer');
            let imc2 = imc * 2;
            ptr.style = "text-align: right; font-size: x-large; width: " + imc2 + "%;";

            var obj = document.getElementById('prInfo');
            obj.style.display = "block";
        }
    </script>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 6;
    include "./includes/dietMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<div class='container'>";
?>
<form id="dietIMC" method="" action="" onsubmit="return false">
    
    <h2><?php echo locale("strTitle_3")?></h2>
    <div class='round'><?php echo locale("strTitle_4")?></div><br>

    <input type="hidden" id="dtUser" name="dtUser" value="1">
    <input type="hidden" id="dtCals" name="dtCals" value="0">

    <table>
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
    <input type="button" value=" <?php echo locale("strCompute")?> " onclick="javascript:if (validateForm()) getIMC();">
    <?php
        // souble the values over 50 to get percent over 100 
        $seg_1 = 37;
        $seg_2 = 13;
        $seg_3 = 10;
        $seg_4 = 10;
        $seg_5 = 10;
        $seg_6 = 20;
    ?>
    <div style="display:none" id="prInfo">
        <div id="imcResult"></div>
        <div class='imcRuler'>
            <div id='imcPointer'>↓</div>
        </div>
        <div class='imcRuler'>
            <div style='width:<?php echo $seg_1?>%; text-align: center; background-color: #1c4d5c;'><?php echo locale("strIMC_1")?></div>
            <div style='width:<?php echo $seg_2?>%; text-align: center; background-color: #2a7512;'><?php echo locale("strIMC_2")?></div>
            <div style='width:<?php echo $seg_3?>%; text-align: center; background-color: #a1a100;'><?php echo locale("strIMC_3")?></div>
            <div style='width:<?php echo $seg_4?>%; text-align: center; background-color: #f39c12;'><?php echo locale("strIMC_4")?></div>
            <div style='width:<?php echo $seg_5?>%; text-align: center; background-color: #e67e22;'><?php echo locale("strIMC_5")?></div>
            <div style='width:<?php echo $seg_6?>%; text-align: center; background-color: #e74c3c;'><?php echo locale("strIMC_6")?></div>
        </div>
        <div class='imcRuler'>
            <div style='width:<?php echo $seg_1?>%; text-align: right;'>18.5</div>
            <div style='width:<?php echo $seg_2?>%; text-align: right;'>25</div>
            <div style='width:<?php echo $seg_3?>%; text-align: right;'>30</div>
            <div style='width:<?php echo $seg_4?>%; text-align: right;'>35</div>
            <div style='width:<?php echo $seg_5?>%; text-align: right;'>40</div>
            <div style='width:<?php echo $seg_6?>%; text-align: right;'>50</div>
        </div>
    </div>
</form>


<?php
    $glossary_IMC = 5;
    $sql = "select * from diet_glossary where IDterm = $glossary_IMC";
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