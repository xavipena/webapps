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
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $page ="Diet";
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
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
                alert("Ompla totes les dades per calcular (" + err + ")");
                return false;
            }
            return true;
        }

</script>

<div>
<?php
/* 
    1g de carbohidratos proporciona 4 calorías;
    1g de proteínas proporciona 4 calorías;
    1g de grasas proporciona 9 calorías;
    1g de alcohol proporciona 7 calorías. 

*/
?>
    <h2>Calcula calories d'una ració d'aliment</h2>
    <form id="calcLoss" action="xDietUser_4.php" method="post" onsubmit="return false;">
        <input type="hidden" id="lossPerDay" name="lossPerDay" value="0">
        <input type="hidden" id="lossLimit" name="lossLimit" value="0">

        <table width="400px">
        <tr>
            <td>Hidrats de carboni</td>
            <td>
                <input type="number" id="myCals" name="myCals" value="0" size="5">&nbsp;Kcal
            </td>
        </tr>
        <tr>
            <td>Proteïnes</td>
            <td>
                <input type="number" id="lossKg" name="lossKg" value="0" size="5">&nbsp;kg
            </td>
        </tr>
        <tr>
            <td>Greixos</td>
            <td>
                <input type="number" id="lossWeeks" name="lossWeeks" value="0" size="5">&nbsp;setmanes
            </td>
        </tr>
        <tr>
            <td>Alcohol</td>
            <td>
                <input type="number" id="lossWeeks" name="lossWeeks" value="0" size="5">&nbsp;setmanes
            </td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
            <td>
                <button type="button" onclick="javascript:if (validateWeightLoss()) getCalories()">Calcula</button>
            </td>
            <td></td>
        </tr>
        </table>
    </form>
    <br>
    <div id="lossResult"></div>
</div>
<?php
include './includes/googleFooter.inc.php';
?>