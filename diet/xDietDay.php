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
        function restoreSelection(thisDate)
        {
            location.href="xDietSelection_4.php?mdate=" + thisDate;
        }
    </script>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 35%), url("./images/pexels-roman-odintsov-4551521.jpg");
            background-size: cover;
        }
     /* settings buttons container */
        .settings {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        /* input type number does not support size attribute */
        input[type='number']{
            width: 60px;
        } 
        .green {
            color: green;
        }
        .greenTitle {
            color: greenyellow;
            font-weight: bold;
        }
        .navigator {
            position:absolute;
            top: 120px;
            right: 50px;
            width: 100px;
        }
        .navigator img {
            cursor: pointer; 
            height: 30px;
        }
    </style>
</head>
<body>
<?php 
    include "./includes/modal.inc.php";
    
    //--- params -------------------------

    if (empty($clean['step'])) {

        $_SESSION['offset'] = 0;
    } 
    else if ($clean['step'] == "p") {

        $_SESSION['offset'] += 1;
    }
    else if ($clean['step'] == "n") {

        if ($_SESSION['offset'] > 0) $_SESSION['offset'] -= 1;
    }
    
    //--- setting ------------------------

    $numCols = empty($_SESSION['numcols']) ? 4 : $_SESSION['numcols'];
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_user_meals";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ----------------------
    
    include "./includes/cards.inc.php";
    include "./includes/dietFunctions.inc.php";

    function printHeader($section) {
        
        if ($_SESSION['details'] == YES)
        {   
            echo "<br><span class='green'>$section</span>"; //<hr>";
            echo "<table width='90%'><thead><tr>".
                        "<th>Producte</th>".
                        "<th style='text-align: right'>Qtat</th>".
                        "<th style='text-align: right'>Kcal</th></tr></thead>";
            echo "<tr><td colspan='3'><hr></td></tr>";
        }
        else 
        {
            echo "<table width='90%'>";
        }
    }

    function printCalForm($db, $date) {

        $GLOBALS['burned'] = 0;
        $sql =  "select burned ".
                "from diet_user_data ".
                "where IDuser = ".$GLOBALS['user']." and IDperiod = ".$GLOBALS['period']." and date = '".$date."'";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {
        
            $GLOBALS['burned'] = $row['burned'];
        }
        echo "<br><hr>";
        echo "<form action='xDietDay_3.php' method='post'>";
        echo "<input type='hidden' id='dtDate' name='dtDate' value='".$date."'>";
        echo "<label for='dtBurn'>Kcal per exercici</label> ";
        echo "<input type='number' id='dtBurn' name='dtBurn' value='".$GLOBALS['burned']."'>";
        echo "&nbsp;<input type='submit' value='Grava'><br><a href='xDietMET.php'>Calcula exercicis</a>";
        echo "</form>";
    }

    function WeightDifference($dietDate, $net) {

        $db = $GLOBALS['db'];
        $output = "";
        $weight_0 = 0.00;
        $weight_f = 0.00;

        $tomorrow = date('Y-m-d', strtotime($dietDate . ' +1 day'));

        $sql = "select weight from diet_user_data where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$GLOBALS['period']." and date = '$tomorrow'";
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) {

            $weight_f = $row['weight'];
            
            $sql = "select weight from diet_user_data where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$GLOBALS['period']." and date = '$dietDate'";
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) 
            {
                $weight_0 = $row['weight'];

                $diff = round($weight_f - $weight_0, 2);
                $calories = CALS_PER_KG * $diff;
                $diffText = $diff > 0 ? " Kg guanyats" : " Kg perduts";
                $diffText = abs($diff).$diffText;
                $output = "<br>En pes real: $diffText<br>Error de $calories calories";
            }
        }
        return $output;
    }

    function explain($id) {

        $ret = " Kcal".$GLOBALS['newCol']."<a href='javascript:explain($id)'>?</a>";
        return $ret;
    }

    function printFooter($tot, $salt, $sugar, $dietDate) {

        $newColNum  = $GLOBALS['newColNum'];
        $rowStart   = $GLOBALS['rowStart'];
        $rowEnd     = $GLOBALS['rowEnd'];
        $newCol     = $GLOBALS['newCol'];

        echo "<br><hr><table class='dietcard'>";
        
        $calsNeeded =  $GLOBALS['diet_recom'] - $GLOBALS['diet_limit'];
        $calsToday  = $tot - $GLOBALS['burned'];
        $net = $calsToday - $calsNeeded;
        $class = $GLOBALS['diet_loss'] > $GLOBALS['diet_limit']  ? "redNumber" : "number";

        echo $rowStart."Calories recomanades".$newColNum.$GLOBALS['diet_recom'].explain(1).$rowEnd;
        echo $rowStart."Decrement calculat</td><td class='$class'>".$GLOBALS['diet_loss'].explain(2).$rowEnd;
        echo $rowStart."Tope per perdre".$newColNum.$GLOBALS['diet_limit'].explain(3).$rowEnd;
        echo $rowStart."Calories per avui".$newColNum.$calsNeeded.explain(4).$rowEnd;
        echo $rowStart."Total de l'àpat".$newColNum.$tot.explain(5).$rowEnd;
        echo $rowStart."Diferència".$newColNum.abs($calsNeeded - $tot).explain(6).$rowEnd;
        echo $rowStart."Exercici fet".$newColNum.$GLOBALS['burned'].explain(7).$rowEnd;
        echo $rowStart."Calories netes".$newColNum.$net.explain(8).$rowEnd;

        $gainLoss = $net;
        if ($calsNeeded > 0) {

            if ($gainLoss > 0) 
            {
                echo $rowStart."Guany".$newColNum.$gainLoss.$rowEnd;
            } else {

                echo $rowStart."Pèrdua".$newColNum.abs($gainLoss).$rowEnd;
            }
        } else {
            
            echo $rowStart."Net".$newColNum."--".$rowEnd;
        }
        echo "</table><br>";

        if ($tot < $calsNeeded) 
        {
            echo "** La ingesta està per sota del necessari per avui<br>";
        }
        if ($GLOBALS['diet_target'] == 'A') {  // aprimar-se
            
            $kgs = round(abs($gainLoss) / CALS_PER_KG, 3);
            if ($gainLoss < 0) 
            {
                echo "** Progressa adequadament"; 
                echo "<br>Pèrdua neta de "; 
            }
            else 
            {
                echo "No anem bé";
                echo "<br>Guany net de "; 
            }
            echo abs($gainLoss)." Kcal (".$kgs." kg)"; 
            echo WeightDifference($dietDate, $net);
        }
        echo "<br>";

        $linkEnd = "&nbsp;<a href='xDietAudit_2.php?date=$dietDate'>Detall</></td></tr>";
        echo "<br><hr><table width='90%'>";
        if ($salt > 0) {
        
            $attr = " style='color:";
            $attr .= $salt > 5 ? "red'" : "white'";
            
            echo $rowStart."Sal consumida</td><td style='text-align: right'><span".$attr.">".$salt." g</span>".$newCol.$linkEnd;
        }
        if ($sugar > 0) {

            $recomPercent = 5; // Recomanat per la OMS
            $calsPerGram = 4;  // Calories per gram de sucre
            $sugarLimit = round(round($calsNeeded * $recomPercent / 100, 0) / $calsPerGram, 0);
            $attr = " style='color:";
            $attr .= $sugar > $sugarLimit ? "red'" : "white'";
            echo $rowStart."Sucre consumit ($sugarLimit g)</td><td style='text-align: right'><span".$attr.">".$sugar." g</span>".$newCol.$linkEnd;
        }
        echo "</table>";
    }

    // Function to find the difference 
    // between two dates.
    function dateDiffInDays($date1, $date2) {
    
        // Calculating the difference in timestamps
        $diff = strtotime($date2) - strtotime($date1);

        // 1 day = 24 hours
        // 24 * 60 * 60 = 86400 seconds
        return abs(round($diff / 86400));
    }

/*
    function NavDate($db) {

        if ($_SESSION['in_period'] == YES) {

            // Get first date from period
            $sql = "select beginDate, endDate from diet_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);

            $endDate = $row['endDate'];
            $today = date("Y-m-d");
            $GLOBALS['fromDate'] = date('Y-m-d', strtotime(' -'.$offset.' day', $toDate));
            $GLOBALS['toDate'] = $endDate > $today ? $today : $endDate;
        }
        else {

            $GLOBALS['toDate'] = date('Y-m-d');
            $GLOBALS['fromDate'] = date('Y-m-d', strtotime(' -7 day'));
        }
        if ($GLOBALS['w'] > 0) {
    
            $days = $GLOBALS['w'] * $GLOBALS['numCols'];
            $GLOBALS['toDate'] = date('Y-m-d', strtotime(' -'.$days.' day'));
        }   
        
        $GLOBALS['prev'] = $GLOBALS['w'] > 0 ? $GLOBALS['w'] - 1 : 0;
        $GLOBALS['next'] = $GLOBALS['w'] + 1;
    }
*/
    //--- new content -------------------- 
        
    $date = empty($clean['dt']) ? "" : $clean['dt'];
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_DIET;
    $selectedMenuOption = 1;
    include "./includes/dietMenu.inc.php";

    $user = "0";
    $period = 0;
    if (isset($_SESSION['diet_user'])) {
        
        if ($_SESSION['diet_user'] != "") {
            
            $user = $_SESSION['diet_user'];
            $period = $_SESSION['diet_period'];
            
            $sql = "select * from diet_periods where Iduser = $user and IDperiod = $period";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);

            $fromDate = $row['beginDate'];
            if (empty($toDate)) {
                
                $toDate = $row['endDate']; // first time
            }
            if ($_SESSION['offset'] > 0) {

                $toDate = date('Y-m-d', strtotime($row['endDate'].' - '.$_SESSION['offset'].' day'));
                if (dateDiffInDays($toDate, $fromDate) < $numCols) {
                
                    // restore one day
                    $toDate = date('Y-m-d', strtotime($toDate.' + 1 day'));
                }            
            }
            
            $sql = "select * from diet_user_periods where Iduser = $user and IDperiod = $period";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);
            
            $diet_recom = $row['recommended'];
            $diet_basal = $row['basal'];
            $diet_limit = $row['limited'];
            $diet_loss  = $row['lossPerDay'];
            $diet_target = $row['target'];
        }
    }
    
    // -------------------------------
    // Show settings
    // -------------------------------
    $dateRange = " ".$fromDate." - ".$toDate;
    echo "<div class='settings'>";
    echo DietSettings($db, SET_DETAILS);
    echo DietSettings($db, SET_IN_PERIOD).$dateRange;
    echo "</div>";
    
    $widthPercent = 25; // fixed to 4 cols

    // -------------------------------
    // Navigator
    // -------------------------------
/*
    $toDate = "";
    NavDate($db);
    $sql =  "select count(distinct date) ".
            "from diet_user_meals ".
            "where Iduser = $user and IDperiod = $period and date <= '$toDate'";

    $result = mysqli_query($db, $sql);
    $cnt = mysqli_fetch_array($result)[0];

    if ($cnt <= $numCols) {

        // restore, no more days backward
        $w -= 1;
        NavDate($db);
    }
*/
    echo "<div class='navigator'>";
    echo "<img src='./images/arrowleft.png'  onclick='location.href=\"xDietDay.php?step=n\"' title='Següent'>";
    echo "<img src='./images/arrowright.png' onclick='location.href=\"xDietDay.php?step=p\"' title='Anterior'>";
    echo "</div>";

    $burned = 0;    
 
    if ($user == "0") echo "No s'ha assignat cap usuari";
    else {

        $biometrics = true;
        if ($diet_recom == 0) {

            // echo "** Actualitza el teu usuari amb els valors de calories";
            $biometrics = false;
        }    
    }    

    if (!$biometrics) {

        AddCard("xDietCalc.php", "diet", "Calories", "No tens dades biomètriques. Actualitza el teu usuari amb els valors de calories", false);
    }
    echo "<table cellpadding='5'><tr>";

    // -------------------------------
    // Diet details
    // -------------------------------

    $numDays = 0;
    $auxDate = "";
    $auxMeal = "";
    $tot = 0;
    $salt = 0;
    $sugar = 0;
    $sql =  "select um.IDproduct pID, um.date date, um.IDmeal id, m.name mname, p.name pname, coalesce(x.name, ' ') xname, ".
            "   um.quantity qty, um.calories cal, d.salt dsalt, d.sugar dsugar, coalesce(x.energy, 0) xcal ".
            "from diet_user_meals um ".
            "left join diet_meals m on m.IDmeal = um.IDmeal ".
            "left join diet_products p on p.IDproduct = um.IDproduct ".
            "left join diet_product_data d on d.IDproduct = um.IDproduct and d.unit = 'Ration' ".
            "left join diet_product_mix x on x.IDmix = um.IDmix ".
            "where um.Iduser = $user and um.IDperiod = $period and um.date <= '$toDate' and um.date >= '$fromDate'". 
            " order by um.date desc, um.IDmeal";
    
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        
        if ($auxDate != $row['date']) {

            // day separation
            if ($auxDate != "") {

                echo "</table>";

                printCalForm($db, $auxDate); // anterior perquè ha canviat
                printFooter($tot, $salt, $sugar, $auxDate);
                echo "</td>";
                $tot = 0;
                $salt = 0;
                $sugar = 0;
                
                $numDays += 1;
                if ($numDays >= $numCols) break;
            }
            echo "<td width='$widthPercent%' style='border:1px solid #aaa'>";

            echo "<span class='greenTitle'>Dia ".$row['date']."</span>";
            // Modifica si está lliure la selecció
            $sql = "select count(*) as cnt from diet_user_selection where IDuser = ".$_SESSION['diet_user'];
            $res = mysqli_query($db, $sql);
            if (mysqli_fetch_array($res)[0] == 0 ) {

                if ($_SESSION['details'] == YES)  {

                    echo "&nbsp;&nbsp;<input type='button' value='Modifica' onclick='javascript:restoreSelection(\"".$row['date']."\")'/>";
                }
            }
            $auxMeal = ""; // start over
            $auxDate = $row['date'];
        }

        // Next meal in day
        if ($auxMeal != $row['id']) {

            if ($auxMeal != "") echo "</table>";
            printHeader($row['mname']);
            $auxMeal = $row['id'];
        }

        if ($_SESSION['details'] == YES) {

            $name = $row['pID'] > 0 ? $row['pname'] : $row['xname']; 
            $kcal = $row['pID'] > 0 ? $row['cal'] : $row['xcal']; 
            echo "<tr><td width='70%'>$name</td>".
                "<td class='number'>".$row['qty']."</td>".
                "<td class='number'>$kcal</td></tr>";
        }
        $tot += $row['qty'] * $row['cal'];
        $salt += $row['dsalt'];
        $sugar += $row['dsugar'];
    }
    echo "</table>";

    if ($numDays == 0 && $auxDate == "") {

        echo "<div class='warning'>No tens cap registre de menjars</div>";
        AddCard("xDietSelection.php", "diet", "Àpats", "Entra els teus àpats per aquí i grava'ls per una data", false);
    }
    else {

        if ($user != "0") {

            // si no ha desbordat, pinta el últim peu
            if ($numDays < $numCols) {

                if ($auxDate == "") $auxDate = date("Y-m-d");
                printCalForm($db, $auxDate); // anterior perquè ha canviat
                printFooter($tot, $salt, $sugar, $auxDate);
            }
        }
    }
?>
</td></tr></table>
<div class='helpHide'></div>
<?php
include './includes/googleFooter.inc.php';
?>