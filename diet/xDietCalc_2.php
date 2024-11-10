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
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 45%), url("./images/pexels-scottwebb-28054.jpg");
            background-size: cover;
        }
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
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 5;
    include "./includes/dietMenu.inc.php";
?>
    <script>      
        debugit = false;

        const woman_1 = 655
        const woman_2 = 9.5
        const woman_3 = 1.8
        const woman_4 = 4.6

        const man_1 = 66.4
        const man_2 = 13.75
        const man_3 = 5
        const man_4 = 6.7
        
        const woman_0 = -161
        const man_0 = 5

        const weight_0 = 10
        const height_0 = 6.25
        const age_0 = 5

        var activityID = new Array();
        activityID["A"] = 1.2;
        activityID["B"] = 1.375;
        activityID["C"] = 1.55;
        activityID["D"] = 1.725;
        activityID["E"] = 1.9;

        var calories = 0;

        function updateUser(frm) 
        {
            document.forms[frm].submit();
        }

        function getLossDietType()
        {
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

        function getQuantity(item, frm)
        {
            if (item == null) return 0;

            var theForm = document.forms[frm];
            var quantity = theForm.elements[item];

            //alert(frm + ":" + theForm + "/" + item + ":" +  quantity);

            var qty = 0;
            if (quantity.value != "")
            {
                qty = parseInt(quantity.value);
            }
            return qty;
        }

        function validateWeightLoss() 
        {
            var err = 0;
            if (getQuantity("lossKg","calcLoss") == 0) err = 1;
            else if (getQuantity("lossWeeks","calcLoss") == 0) err = 2;
            else if (getLossDietType() == 0) err = 3;

            if (err) 
            {
                alert("<?php echo locale("strMsg_2")?>");
                return false;
            }
            return true;
        }

        function addRow(table, col1, col2, unit) 
        {
            var tr = document.createElement('tr');
            
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            td2.className = "number";
            
            var text1 = document.createTextNode(col1);
            var textNode = "";
            if (col2 != "") textNode = col2 + " " + unit;
            var text2 = document.createTextNode(textNode);
            
            td1.appendChild(text1);
            td2.appendChild(text2);
            
            tr.appendChild(td1);
            tr.appendChild(td2);

            table.appendChild(tr);
        }

        function getLossCalories() 
        {
            calories = document.getElementById("myCals").value;
            if (calories == 0) 
            {
                alert("<?php echo locale("strMsg_1")?>");
                return;
            }
            let rowStyle = "style='text-align:right'";
            let weeks = document.getElementById("lossWeeks").value;
            let calPerKg = 7700;
            let kgs = document.getElementById("lossKg").value ;
            let calToLose = kgs * calPerKg;
            let calPerWeek = Math.round(calToLose / weeks);
            let calPerDay = Math.round(calPerWeek / 7);

            var table = document.createElement('table');
            table.className = 'dietcard';
            table.width = "400px";

            resDiv = document.getElementById("lossResult");

            addRow(table, '<?php echo locale("strMsg_9")?> ' + kgs + ' kg', calToLose, 'Kcal');
            addRow(table, '<?php echo locale("strMsg_4")?>', weeks * 7, 'dies');
            addRow(table, '<?php echo locale("strMsg_3")?>', calPerDay, 'Kcal');

            var limitPerDay = getLossDietType();
            let tolerance = limitPerDay * 0.1;
            limitPerDay += tolerance
            if (calPerDay > limitPerDay) 
            {
                addRow(table, '<?php echo locale("strMsg_5")?>', limitPerDay, 'Kcal');
                
                // Recalculate with new limit
                let numDays = Math.round(calToLose / limitPerDay);
                addRow(table, '<?php echo locale("strMsg_10")?>', numDays, 'dies');
                document.getElementById("lossLimit").value = limitPerDay;
            }
            else 
            {
                if (Math.abs(limitPerDay - calPerDay) > 50) 
                {
                    var newLimit = Math.round(calories - calPerDay);
                    addRow(table, '<?php echo locale("strMsg_6")?>', newLimit, 'Kcal');
                    // recalcua dies amb la diferència
                    var newKg = (limitPerDay * 7 * weeks) / 7700;
                    addRow(table, '<?php echo locale("strMsg_7")?>', newKg, 'kg');
                    var newDays = Math.round(calToLose / limitPerDay);
                    var newWeeks = Math.round(newDays / 7);
                    addRow(table, '<?php echo locale("strMsg_8")?>', newDays, '<?php echo locale("strDays")?>');
                    document.getElementById("lossLimit").value = newLimit;

                    limitPerDay = newLimit;
                }
            }    
            // update form fields
            document.getElementById("lossPerDay").value = calPerDay;
            resDiv.appendChild(table);

            if (limitPerDay > 0)
            {
                addRow(table, '-', 0, '');

                var newDiv = document.createElement('div');
                newDiv.className = "round";
                newDiv.innerHTML = "<?php echo locale("strMsg_11")?> " + limitPerDay + " Kcal <?php echo locale("strMsg_12")?><br>";
                let mycals = document.getElementById('myCals').value;
                let newCals = myCals.value - limitPerDay;
                newDiv.innerHTML += "<?php echo locale("strMsg_13")?> " + newCals + " Kcal<br>";
                document.getElementById("topDiet").value = newCals;
                resDiv.appendChild(newDiv);
            }
<?php
    if (isset($_SESSION['diet_user'])) 
    {
        if ($_SESSION['diet_user'] != "") 
        {
?>            
            resDiv.innerHTML += "<br><input type=\"button\" value=\"<?php echo locale("strUpdUser")?>\" onclick=\"javascript:if (validateWeightLoss()) updateUser('calcLoss')\">";
<?php
        }
    }
?>                         

        }
</script>

<?php
    if (!isset($_SESSION['diet_user'])) 
    {
        echo "<a href='xDietUser_1.php'>".locale("strMsg_14")."</a> ".locale("strText_6")."<br><br>";
    }
    echo "<div class='container'>";
?>
    <form id="calcLoss" action="xDietUser_4.php" method="post" onsubmit="return false;">
        
        <h2><?php echo locale("strCardHelp_2")?></h2>
        <div class='round'><?php echo locale("strText_7")?></div><br>

        <input type="hidden" id="lossPerDay" name="lossPerDay" value="0">
        <input type="hidden" id="lossLimit" name="lossLimit" value="0">
        <input type="hidden" id="topDiet" name="topDiet" value="0">

        <table>
            <tr>
                <td><?php echo locale("strFormCal_1")?></td>
                <td><input type="number" class="number-input" id="myCals" name="myCals" value="0">&nbsp;Kcal</td>
            </tr><tr>
                <td><?php echo locale("strFormCal_2")?></td>
                <td><input type="number" class="number-input" id="lossKg" name="lossKg" value="0">&nbsp;kg</td>
            </tr><tr>
                <td><?php echo locale("strFormCal_3")?></td>
                <td><input type="number" class="number-input" id="lossWeeks" name="lossWeeks" value="0">&nbsp;setmanes</td>
            </tr><tr>
                <td colspan="2"><fieldset>
                    <legend><?php echo locale("strFormDiet")?></legend>
                    <input type="radio" name="lossDiet" id="500" value="500" /><?php echo locale("strFormDiet_1")?><br>
                    <input type="radio" name="lossDiet" id="600" value="600" /><?php echo locale("strFormDiet_2")?><br>
                    <input type="radio" name="lossDiet" id="700" value="700" /><?php echo locale("strFormDiet_3")?><br>
                </fieldset></td>
            </tr><tr>
                <td><input type="button" value="<?php echo locale("strCompute")?>" onclick="javascript:if (validateWeightLoss()) getLossCalories()"></td>
            </tr>
        </table>
        <br>
        <div id="lossResult"></div>
    </form>
<?php
    // cards in vertical list
    echo "<div class='vcontainer'>";
        $glossary_CAL = 4;
        $sql = "select * from diet_glossary where IDterm = $glossary_CAL";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
        }
        AddCard("xDietIMC.php", "diet", locale("strNext"), "Calcula el teu índex de massa corporal", true);
    echo "</div>";
    echo "</div>";
?>
<script>
<?php
    if (isset($_SESSION['diet_user'])) 
    {
        if ($_SESSION['diet_user'] != "") 
        {
            $sql = "select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) 
            {
                echo "document.getElementById('myCals').value = '".$row['recommended']."';".PHP_EOL;
                echo "document.getElementById('lossKg').value = '".$row['lossKg']."';".PHP_EOL;
                echo "document.getElementById('lossWeeks').value = '".$row['lossWeeks']."';".PHP_EOL;

                if ($row['lossDiet'] > 0)
                {
                    echo "var radiobtn = document.getElementsByName('lossDiet');".PHP_EOL;
                    echo "radiobtn[0].value = ".$row['lossDiet'].";".PHP_EOL;
                }
                echo "calories = ".$row['recommended'].";".PHP_EOL;
            }
        }
    }
?>
</script>
<?php
    include './includes/googleFooter.inc.php';
?>