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
    
    $date = empty($clean['date']) ? "" : $clean['date'];
    
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
             "<thead>".
                "<th>Sucre</th>".
                "<th>Sal</th>".
                "<th>Alcohol</th>".
              "</thead>";
    }

    function PrintFooter() {

        echo "<tfoot>";
        echo "<tr>";
        echo "<td class='number'>".$GLOBALS['tot_1']."</td>";
        echo "<td class='number'>".$GLOBALS['tot_2']." g</td>";
        echo "<td class='number'>".$GLOBALS['tot_3']." g</td>";
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
        AddCard("./login/login.php", "diet", "LogIn", "Identificat per analitzar les teves dades d'ingesta", false);
        include './includes/googleFooter.inc.php';
        exit();
    }
    else {

        $sql = "delete from diet_audits where IDuser = $user and audit = ".AUDIT_2;
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

    $numCols = !empty($date) ? 0 : 6; // week
    $numDays = 0;

    $auxDate = "";

    printHeader();

    $sql =  "select um.IDproduct pID, um.date date, um.IDmeal id, p.name pname, um.IDmix IDmix, x.name xname, ".
            "   um.quantity qty, um.calories cal, d.sugar sugar, d.salt salt, d.alcohol alcohol, x.energy xcal ".
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

        $sql =  "update diet_audits set ". 
                " quantity  = quantity + ".$row['qty'].
                ",grams_1   = grams_1 + ".$row['sugar'].
                ",grams_2   = grams_2 + ".$row['salt'].
                ",grams_3   = ".$row['alcohol']. // graus alcohòlics no es sumen
                " where IDuser    = ".$user.
                "   and IDproduct = ".$row['pID'].
                "   and IDmix     = ".$row['IDmix'].
                "   and audit     = ".AUDIT_2;
                
        mysqli_query($db, $sql);
        if (mysqli_affected_rows($db) == 0) 
        {
            
            $sql =  "insert into diet_audits set ". 
                    " IDuser    = ".$user.
                    ",IDproduct = ".$row['pID'].
                    ",IDmix     = ".$row['IDmix'].
                    ",quantity  = ".$row['qty'].
                    ",kcal_1    = 0".
                    ",kcal_2    = 0".
                    ",kcal_3    = 0".
                    ",kcal_4    = 0".
                    ",kcal_5    = 0".
                    ",kcal_6    = 0".
                    ",grams_1   = ".$row['sugar'].
                    ",grams_2   = ".$row['salt'].
                    ",grams_3   = ".$row['alcohol'].
                    ",grams_4   = 0".
                    ",grams_5   = 0".
                    ",grams_6   = 0".
                    ",audit     = ".AUDIT_2;

            if (!mysqli_query($db, $sql))
            {
                echo mysqli_error($db);
            }
        }

        $tot_1 += $row['sugar'];
        $tot_2 += $row['salt'];
        $tot_3 += $row['alcohol'];

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
    printFooter();
    if ($tot_1 == 0) {

        echo "No tens cap registre de menjars per aquesta data";
        include './includes/googleFooter.inc.php';
        exit;
    }
    /*
    La Organización Mundial de la Salud recomienda:
    sucre 5% de la ingesta
    */

    $tot_1 = round($tot_1);
    $tot_2 = round($tot_2);
    $tot_3 = round($tot_3);

    $user = $_SESSION['diet_user'];
    $sql = "select * from diet_users where Iduser = ".$user;
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    $diet_recom = $row['recommended'];
    $diet_limit = $row['limited'];
    $calsNeeded =  $diet_recom - $diet_limit;
    $recomPercent = 5; // Recomanat per la OMS
    $calsPerGram = 4;  // Calories per gram de sucre
    $sugarLimit = round(round($calsNeeded * $recomPercent / 100, 0) / $calsPerGram, 0);

    $theoric_1 = $sugarLimit;
    $theoric_2 = 5;
    $theoric_3 = 0;

    // Sugar
    $recomPercent = 10;
    $upperLimit_1 = $sugarLimit;
    $lowerLimit_1 = 0;

    // salt
    $upperLimit_2 = 6;
    $lowerLimit_2 = 0;

    // alcohol
    $upperLimit_3 = 1;
    $lowerLimit_3 = 0;

    //$days = $date != "" ? 1 : 7;
    $begin = new DateTime($fromDate);
    $end   = new DateTime($toDate);
    $days = $begin->diff($end)->days + 1;

    $per_1 = round($tot_1 / $days, 2); // by day
    $per_2 = round($tot_2 / $days, 2); // by day
    $per_3 = round($tot_3 / $days, 2); // by day

    echo "<br>";
    echo "<input type='button' value='Veure els detalls per producte' onclick=\"location.href='xDietAuditDetails_2.php'\">";
    echo "&nbsp;<input type='button' value=' ? ' onclick='javascript:explain(10)'>";
    echo "<br><br>";
    echo "<table class='dietcard'>".
            "<thead><tr>".
                "<th></th>".
                "<th>Setmanal</th>".
                "<th>Diari</th>".
                "<th>Comentari</th>".
            "</tr></thead>";

    $explain_1 = "";
    $explain_2 = "";
    $explain_3 = "";

    if ($per_1 > $lowerLimit_1) $explain_1 = "Recomanat fins a $upperLimit_1 g/dia";
    if ($per_1 > $upperLimit_1) $explain_1 = "Alt. Cal disminuir la ingesta de sucres";

    if ($per_2 > $lowerLimit_2) $explain_2 = "Recomanat fins a $upperLimit_2 g/dia";
    if ($per_2 > $upperLimit_2) $explain_2 = "Alt. Cal disminuir la ingesta de sal";
    
    if ($per_3 > $lowerLimit_3) $explain_3 = "MIllor sense alcohol";

    echo $rowStart."Total sucre:".$newColNum.$tot_1." g".$newColNum.$per_1." g".$newCol.$explain_1.$rowEnd;
    echo $rowStart."Total sal:".$newColNum.$tot_2." g".$newColNum.$per_2." g".$newCol.$explain_2.$rowEnd;
    echo $rowStart."Total alcohol:".$newColNum.$tot_3." º".$newColNum.$per_3." º".$newCol.$explain_3.$rowEnd;

    echo "</table>";
    
    echo "<br>";
    echo "<table class='dietcard'>";
    echo $rowStart."<canvas id='chart_1' width='300'></canvas>".$newCol."<canvas id='chart_2' width='300'></canvas>".$rowEnd;
    echo "</table>";
    
    $timestamp = strtotime($fromDate);
    $formatted_1 = date('d-m-Y', $timestamp);
    $timestamp = strtotime($toDate);
    $formatted_2 = date('d-m-Y', $timestamp);

    $_SESSION['dateRange'] = "Des del dia $formatted_1 fins $formatted_2";
    $max = round((max($per_1, $per_2, $per_3) + 10) / 10, 0) * 10;
?>
<script>
    dr = document.getElementById('dateRange');
    dr.innerHTML = "<?php echo $_SESSION['dateRange'] ?>";

    // helpers

    const Utils = ChartUtils.init();

    // setup
    
    const data_1 = {
    labels: ['Sucre','Sal','Alcohol'],
    datasets: [
            {
                label: 'Mitja',
                data: [<?php echo $per_1.",".$per_2.",".$per_3 ?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    const data_2 = {
    labels: ['Sucre','Sal','Alcohol'],
    datasets: [
            {
                label: 'Recomanat',
                data: [<?php echo $theoric_1.",".$theoric_2.",".$theoric_3 ?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    // config

    const config_1 = {
        type: 'bar',
        data: data_1,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Real'
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: <?php echo $max ?>,
                }
            }
        }
    };

    const config_2 = {
        type: 'bar',
        data: data_2,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Referència'
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: <?php echo $max ?>,
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
</script>
<?php
    include './includes/googleFooter.inc.php';
?>