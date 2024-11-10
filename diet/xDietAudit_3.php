<?php
    session_start();
    $page = "diet";
    $runner_id ="";
    $useCharts = "Y";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];

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
             "<thead>".
                "<th>Calories</th>".
              "</thead>";
    }

    function PrintFooter() {

        echo "<tfoot>";
        echo "<tr>";
        echo "<td class='number'>".$GLOBALS['tot_1']."</td>";
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

        $sql = "delete from diet_audits where IDuser = $user and audit = ".AUDIT_3;
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

    $numCols = 6; // week
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

        $name = $row['pID'] > 0 ? $row['pname'] : $row['xname']; 
        $kcal = $row['pID'] > 0 ? $row['cal'] : $row['xcal']; 

        $sql =  "update diet_audits set ". 
                " quantity  = quantity + ".$row['qty'].
                ",kcal_1    = kcal_1 + ".$kcal.
                " where IDuser    = ".$user.
                "   and IDproduct = ".$row['pID'].
                "   and IDmix     = ".$row['IDmix'].
                "   and audit     = ".AUDIT_3;

        mysqli_query($db, $sql);
        if (mysqli_affected_rows($db) == 0) 
        {
            
            $sql =  "insert into diet_audits set ". 
                    " IDuser    = ".$user.
                    ",IDproduct = ".$row['pID'].
                    ",IDmix     = ".$row['IDmix'].
                    ",quantity  = ".$row['qty'].
                    ",kcal_1    = ".$kcal.
                    ",kcal_2    = 0".
                    ",kcal_3    = 0".
                    ",kcal_4    = 0".
                    ",kcal_5    = 0".
                    ",kcal_6    = 0".
                    ",grams_1   = 0".
                    ",grams_2   = 0".
                    ",grams_3   = 0".
                    ",grams_4   = 0".
                    ",grams_5   = 0".
                    ",grams_6   = 0".
                    ",audit     = ".AUDIT_3;

            if (!mysqli_query($db, $sql))
            {
                echo mysqli_error($db);
            }
        }

        $tot_1 += $kcal;

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

    printFooter();
    if ($tot_1 == 0) {

        echo "<div class='warning'>No tens cap registre de menjars per aquestes dates</div>";
        AddCard("xDietSelection.php", "diet", "Àpats", "Entra els teus àpats per aquí i grava'ls per una data", false);
        include './includes/googleFooter.inc.php';
        exit();
    }

    // -------------------------
    // Get user data
    // -------------------------

    $sql = "select * from diet_users where IDuser = $user";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    $theoric_1 = $row['recommended'];

    $upperLimit_1 = $row['recommended'];
    $lowerLimit_1 = $row['basal'] + $row['thermogenesis'];

    $begin = new DateTime($fromDate);
    $end   = new DateTime($toDate);
    $days = $begin->diff($end)->days + 1;
    $per_1 = round($tot_1 / $days, 2); // by day

    echo "<br>";
    echo "<input type='button' value='Veure els detalls per producte' onclick=\"location.href='xDietAuditDetails_3.php'\">";
    echo "&nbsp;<input type='button' value=' ? ' onclick='javascript:explain(11)'>";
    echo "<br><br>";
    echo "<table class='dietcard'>".
            "<thead><tr>".
                "<th></th>".
                "<th>Setmanal</th>".
                "<th>Diari</th>".
                "<th>Comentari</th>".
            "</tr></thead>";

    $explain_1 = "";

    if ($per_1 > $upperLimit_1) $explain_1 = "Alt. Cal disminuir la ingesta de calories, revisa el detall.";
    else if ($per_1 < $lowerLimit_1) $explain_1 = "Molt baix. Cal menjar més o et falten ingestes per posar.";

    echo $rowStart."Total calories".$newColNum.$tot_1." Kcal".$newColNum.$per_1." Kcal".$newCol.$explain_1.$rowEnd;

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
?>
<script>
    dr = document.getElementById('dateRange');
    dr.innerHTML = "<?php echo $_SESSION['dateRange'] ?>";

    // helpers

    const Utils = ChartUtils.init();

    // setup
    
    const data_1 = {
    labels: ['Superior','Inferior'],
    datasets: [
            {
                label: 'Recomanat',
                data: [<?php echo $upperLimit_1.",".$lowerLimit_1 ?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    const data_2 = {
    labels: ['Calories'],
    datasets: [
            {
                label: 'Real',
                data: [<?php echo $per_1 ?>],
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
                    text: 'Recomanant'
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: <?php echo $upperLimit_1 ?>,
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
                    text: 'Real'
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: <?php echo $upperLimit_1 ?>,
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