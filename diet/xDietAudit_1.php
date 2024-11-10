<?php
    session_start();
    $page = "diet";
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $useCharts = "Y";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 40%), url("./images/pexels-pixabay-210607.jpg");
            background-size: cover;
        }
        .green {
            color: green;
        }
        .greenTitle {
            color: greenyellow;
            font-weight: bold;
        }
        .red {
            color: red;
        }
    </style>
</head>
<body>
<?php 
    include "./includes/modal.inc.php";

    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
    //--- Settings ---------------------

    $_SESSION['pageID'] = PAGE_DIET_AUDIT;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    function printHeader() {

        echo "<div id='dateRange' class='greenTitle'>Des del dia ___</div>";
        echo "<br>";
        echo "<table class='dietcard'>".
             "<thead><tr>".
                    "<th>Kcal</th>".
                    "<th>".locale("strCarbohidrate")."</th>".
                    "<th>Kcal</th>".
                    "<th>".locale("strProtein")."</th>".
                    "<th>Kcal</th>".
                    "<th>".locale("strFat")."</th>".
                    "<th>Kcal</th>".
                    "<th>".locale("strFiber")."</th>".
                    "<th>Kcal</th>".
                    "<th>".locale("strAlcohol")."</th>".
                    "<th>Kcal</th>".
             "</tr></thead>";
    }

    function PrintFooter() {

        echo "<tfoot>";
        echo "<tr>";
        echo "<td class='number'>".$GLOBALS['tot_1']."</td>";
        echo "<td class='number'>".$GLOBALS['tot_2']." g</td>";
        echo "<td class='number'>".$GLOBALS['tot_22']."</td>";
        echo "<td class='number'>".$GLOBALS['tot_3']." g</td>";
        echo "<td class='number'>".$GLOBALS['tot_32']."</td>";
        echo "<td class='number'>".$GLOBALS['tot_4']." g</td>";
        echo "<td class='number'>".$GLOBALS['tot_42']."</td>";
        echo "<td class='number'>".$GLOBALS['tot_5']." g</td>";
        echo "<td class='number'>".$GLOBALS['tot_52']."</td>";
        echo "<td class='number'>".$GLOBALS['tot_6']." g</td>";
        echo "<td class='number'>".$GLOBALS['tot_62']."</td>";
        echo "</tr>";
        echo "</tfoot>";
        echo "</table>";
    }

    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    $user = "0";
    $period = 0;
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
            
            $user = $_SESSION['diet_user'];
            $period = $_SESSION['diet_period'];
        }
    }
    if ($user == "0") {
    
        echo "<div class='warning'>No s'ha assignat cap usuari</div>";
        AddCard("./login/login.php", "diet", "LogIn", locale("strCardHelp_4"), false);
        include './includes/googleFooter.inc.php';
        exit();
    }
    else {

        $sql = "delete from diet_audits where IDuser = $user and audit = ".AUDIT_1;
        mysqli_query($db, $sql);
    }

    $today = date("Y-m-d");
    $sql = "select beginDate, endDate from diet_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    $fromDate = $row['beginDate'];
    $toDate = $row['endDate'] > $today ? $today : $row['endDate'];

    $densidad = 0.8;

    $tot_1 = 0;
    $tot_2 = 0;
    $tot_3 = 0;
    $tot_4 = 0;
    $tot_5 = 0;
    $tot_6 = 0;

    $tot_22 = 0;
    $tot_32 = 0;
    $tot_42 = 0;
    $tot_52 = 0;
    $tot_62 = 0;

    $numCols = 6; //week
    $numDays = 0;
    $auxDate = "";

    printHeader();

    $sql =  "select um.IDproduct pID, um.date date, um.IDmeal id, p.name pname, um.IDmix IDmix, x.name xname, ".
            "   um.quantity qty, um.calories cal, d.carbohydrate carboh, d.protein protein, d.fat fat, d.fibre fibre, d.grams grams, d.alcohol alcohol, x.energy xcal ".
            "from diet_user_meals um ".
            "left join diet_products p on p.IDproduct = um.IDproduct ".
            "left join diet_product_data d on d.IDproduct = um.IDproduct and d.unit = 'Ration' ".
            "left join diet_product_mix x on x.IDmix = um.IDmix ".
            "where um.Iduser = $user and um.date between '$fromDate' and '$toDate' ". 
            "order by um.date desc, um.IDmeal";
    
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        if ($row['pID'] == 0) continue;
        
        $name = $row['pID'] > 0 ? $row['pname'] : $row['xname']; 
        $kcal = $row['pID'] > 0 ? $row['cal'] : $row['xcal']; 

        /* 
            1g de carbohidratos proporciona 4 Kcal
            1g de proteínas proporciona 4 Kcal
            1g de grasas proporciona 9 Kcal
            1g de alcohol proporciona 7 Kcal 
            1g de fibra proporciona 2 Kcal

            Calcular calorias del alcohol:
            Se multiplica la graduación alcohólica de la bebida por la cantidad de bebida a consumir en ml, 
            y el resultado hay que multiplicarlo por 0.8 ( que corresponde a la densidad del alcohol) y 
            dividirlo todo por 100.
        */
        $kcal_2 = $row['carboh'] * 4;
        $kcal_3 = $row['protein'] * 4;
        $kcal_4 = $row['fat'] * 9;
        $kcal_5 = $row['fibre'] * 2;
        $alcgrs = round(($row['alcohol'] * $row['grams'] * $densidad) / 100, 2);
        $kcal_6 = round($alcgrs * 7, 2);

        $sql =  "update diet_audits set ". 
                " quantity  = ".$row['qty'].
                ",kcal_1    = kcal_1 + ".$kcal.
                ",kcal_2    = kcal_2 + ".$kcal_2.
                ",kcal_3    = kcal_3 + ".$kcal_3.
                ",kcal_4    = kcal_4 + ".$kcal_4.
                ",kcal_5    = kcal_5 + ".$kcal_5.
                ",kcal_6    = kcal_6 + ".$kcal_6.
                ",grams_1   = 0".
                ",grams_2   = grams_2 + ".$row['carboh'].
                ",grams_3   = grams_3 + ".$row['protein'].
                ",grams_4   = grams_4 + ".$row['fat'].
                ",grams_5   = grams_5 + ".$row['fibre'].
                ",grams_6   = grams_6 + ".$alcgrs.
                " where IDuser    = ".$user.
                "   and IDproduct = ".$row['pID'].
                "   and IDmix     = ".$row['IDmix'].
                "   and audit     = ".AUDIT_1;

        mysqli_query($db, $sql);
        if (mysqli_affected_rows($db) == 0) 
        {
            
            $sql =  "insert into diet_audits set ". 
                    " IDuser    = ".$user.
                    ",IDproduct = ".$row['pID'].
                    ",IDmix     = ".$row['IDmix'].
                    ",quantity  = ".$row['qty'].
                    ",kcal_1    = ".$kcal.
                    ",kcal_2    = ".$kcal_2.
                    ",kcal_3    = ".$kcal_3.
                    ",kcal_4    = ".$kcal_4.
                    ",kcal_5    = ".$kcal_5.
                    ",kcal_6    = ".$kcal_6.
                    ",grams_1   = 0".
                    ",grams_2   = ".$row['carboh'].
                    ",grams_3   = ".$row['protein'].
                    ",grams_4   = ".$row['fat'].
                    ",grams_5   = ".$row['fibre'].
                    ",grams_6   = ".$alcgrs.
                    ",audit     = ".AUDIT_1;

            if (!mysqli_query($db, $sql))
            {
                echo mysqli_error($db);
            }
        }

        $tot_1 += $kcal;
        $tot_2 += $row['carboh'];
        $tot_3 += $row['protein'];
        $tot_4 += $row['fat'];
        $tot_5 += $row['fibre'];
        $tot_6 += $alcgrs;

        $tot_22 += $kcal_2;
        $tot_32 += $kcal_3;
        $tot_42 += $kcal_4;
        $tot_52 += $kcal_5;
        $tot_62 += $kcal_6;

        // not needed for current period
        /*
        if ($auxDate != $row['date']) {

            // day separation
            if ($auxDate != "") {

                $numDays += 1;
                if ($numDays > $numCols) break;
            }
            $auxDate = $row['date'];
        }
        */
    }
    $tot_1 = round($tot_1);
    $tot_2 = round($tot_2);
    $tot_3 = round($tot_3);
    $tot_4 = round($tot_4);
    $tot_5 = round($tot_5);
    $tot_6 = round($tot_6);

    $tot_22 = round($tot_22);
    $tot_32 = round($tot_32);
    $tot_42 = round($tot_42);
    $tot_52 = round($tot_52);
    $tot_62 = round($tot_62);

    printFooter();
    if ($tot_1 == 0) {

        echo locale("strMsg_15");
        include './includes/googleFooter.inc.php';
        exit;
    }
/*
La Organización Mundial de la Salud recomienda:

    50-55% de hidratos de carbono.
    30-35% de lípidos o grasas.
    12-15% de proteínas.
    Cantidades determinadas de fibra, vitaminas y minerales.

*/
    $theoric_2 = 52.0;
    $theoric_3 = 31.0;
    $theoric_4 = 13.0;
    $theoric_5 = 1.5;
    $theoric_6 = 0.5;

    $upperLimit_2 = 55;
    $lowerLimit_2 = 50;
    $upperLimit_3 = 35;
    $lowerLimit_3 = 30;
    $upperLimit_4 = 15;
    $lowerLimit_4 = 12;
    $upperLimit_5 = 10;
    $lowerLimit_5 = 1;

    echo "<br>";
    echo "<input type='button' value='".locale("strDetails")."' onclick=\"location.href='xDietAuditDetails_1.php'\">";
    echo "&nbsp;<input type='button' value=' ? ' onclick='javascript:explain(9)'>";
    echo "<br><br>";
    echo "<table class='dietcard'>";
    $per_2 = round($tot_22 * 100 / $tot_1, 2);
    $per_3 = round($tot_32 * 100 / $tot_1, 2);
    $per_4 = round($tot_42 * 100 / $tot_1, 2);
    $per_5 = round($tot_52 * 100 / $tot_1, 2);
    $per_6 = round($tot_62 * 100 / $tot_1, 2);

    $explain_2 = "";
    $explain_3 = "";
    $explain_4 = "";
    $explain_5 = "";

    if ($per_2 < $lowerLimit_2) $explain_2 = locale("strDiet_1");
    if ($per_2 > $upperLimit_2) $explain_2 = locale("strDiet_2");
    if ($per_3 < $lowerLimit_3) $explain_3 = locale("strDiet_3");
    if ($per_3 > $upperLimit_3) $explain_3 = locale("strDiet_4");
    if ($per_4 < $lowerLimit_4) $explain_4 = locale("strDiet_5");
    if ($per_4 > $upperLimit_4) $explain_4 = locale("strDiet_6");
    if ($per_5 < $lowerLimit_5) $explain_5 = locale("strDiet_7");
    if ($per_5 > $upperLimit_5) $explain_5 = locale("strDiet_8");

    echo $rowStart."Total Kcal:</td><td class='number green'>$tot_1</td><td class='number green'>100%".$rowEnd;
    echo $rowStart."Total ".locale("strCarbohidrate").":".$newColNum.$tot_22.$newColNum.$per_2."%".$newCol.$explain_2.$rowEnd;
    echo $rowStart."Total ".locale("strProtein").":".$newColNum.$tot_32.$newColNum.$per_3."%".$newCol.$explain_3.$rowEnd;
    echo $rowStart."Total ".locale("strFat").":".$newColNum.$tot_42.$newColNum.$per_4."%".$newCol.$explain_4.$rowEnd;
    echo $rowStart."Total ".locale("strFiber").":".$newColNum.$tot_52.$newColNum.$per_5."%".$newCol.$explain_5.$rowEnd;
    echo $rowStart."Total ".locale("strAlcohol").":".$newColNum.$tot_62.$newColNum.$per_6."%".$newCol.$rowEnd;

    $sumEsc = $tot_22 + $tot_32 + $tot_42 + $tot_52 + $tot_62;
    $sumPer = $per_2 + $per_3 + $per_4 + $per_5 + $per_6;
    echo $rowStart."Total Kcal:".$newColNum.$sumEsc."</td><td class='number red'>$sumPer%".$rowEnd;
    echo "</table>";
    
    echo "<br>";
    echo "<table class='dietcard'>";
    echo $rowStart."<canvas id='chart_1' width='300'></canvas>".
           $newCol."<canvas id='chart_2' width='300'></canvas>".
           $newCol."<canvas id='chart_3' width='300'></canvas>".$rowEnd;
    echo "</table>";
    
    $timestamp = strtotime($fromDate);
    $formatted_1 = date('d-m-Y', $timestamp);
    $timestamp = strtotime($toDate);
    $formatted_2 = date('d-m-Y', $timestamp);

    $_SESSION['dateRange'] = locale("strFromDay")." $formatted_1 ".locale("strTo")." $formatted_2";

// Referència
/*
    En la dieta de los españoles, las proteinas aportan el 19% del aporte calorico total, los glucidos el 40%, los lipidos el 39% y la fibra el 2%.
    Las calorias ingeridas sobrepasan en un 17% a las recomendadas e ingerimos una cantidad excesiva de alimentos de origen animal.
*/
?>
<script>
    dr = document.getElementById('dateRange');
    dr.innerHTML = "<?php echo $_SESSION['dateRange'] ?>";

    // helpers

    const Utils = ChartUtils.init();

    // setup
    
    const data_1 = {
    labels: ['<?php echo locale("strCarbohidrate")?>',
             '<?php echo locale("strProtein")?>',
             '<?php echo locale("strFat")?>',
             '<?php echo locale("strFiber")?>',
             '<?php echo locale("strAlcohol")?>'],
    datasets: [
            {
                label: '<?php echo locale("strPercent")?>',
                data: [<?php echo $per_2.",".$per_3.",".$per_4.",",$per_5.",".$per_6 ?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    const data_2 = {
    labels: ['<?php echo locale("strCarbohidrate")?>',
             '<?php echo locale("strProtein")?>',
             '<?php echo locale("strFat")?>',
             '<?php echo locale("strFiber")?>',
             '<?php echo locale("strAlcohol")?>'],
    datasets: [
            {
                label: '<?php echo locale("strRecommended")?>',
                data: [<?php echo $theoric_2.",".$theoric_3.",".$theoric_4.",",$theoric_5.",".$theoric_6 ?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    const data_3 = {
    labels: ['<?php echo locale("strCarbohidrate")?>',
             '<?php echo locale("strProtein")?>',
             '<?php echo locale("strFat")?>',
             '<?php echo locale("strFiber")?>',
             '<?php echo locale("strAlcohol")?>'],
    datasets: [
            {
                label: '<?php echo locale("strTrending")?>',
                data: [40, 19, 39, 2, 0],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    // config

    const config_1 = {
        type: 'pie',
        data: data_1,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: '<?php echo locale("strPlot_1")?>'
                }
            }
        }
    };

    const config_2 = {
        type: 'pie',
        data: data_2,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: '<?php echo locale("strPlot_2")?>'
                }
            }
        }
    };

    const config_3 = {
        type: 'pie',
        data: data_3,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: '<?php echo locale("strPlot_3")?>'
                }
            }
        }
    };

    // rendering

    const Chart_1 = new Chart(
        document.getElementById('chart_1'),
        config_1
    );

    const Chart_2 = new Chart(
        document.getElementById('chart_2'),
        config_2
    );

    const Chart_3 = new Chart(
        document.getElementById('chart_3'),
        config_3
    );
</script>
<?php
    include './includes/googleFooter.inc.php';
?>