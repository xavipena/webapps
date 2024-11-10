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
        .banner {
            color: red;
            opacity: 0.3;
            font-size: 200px;
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

    //echo "<div class='dietPageContainer'>";
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

        $sql = "delete from diet_audits where IDuser = $user and audit = ".AUDIT_4;
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

    $numCols = 6; // week
    $numDays = 0;
    $auxDate = "";

    printHeader();

    $sql =  "select um.IDmeal id, um.IDproduct pID, um.IDmix IDmix, x.name xname, um.quantity qty, um.calories cal, x.energy xcal ".
            "from diet_user_meals um ".
            "left join diet_product_data d on d.IDproduct = um.IDproduct and d.unit = 'Ration' ".
            "left join diet_product_mix x on x.IDmix = um.IDmix ".
            "where um.Iduser = $user and um.date between '$fromDate' and '$toDate' ". 
            "order by um.date desc, um.IDmeal";

    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $kcal = $row['pID'] > 0 ? $row['cal'] : $row['xcal']; 

        $sql =  "update diet_audits set ". 
                " quantity  = quantity + ".$row['qty'].
                ",kcal_1    = kcal_1 + ".$kcal.
                " where IDuser    = ".$user.
                "   and IDperiod  = ".$period.
                "   and IDproduct = ".$row['id'].
                "   and audit     = ".AUDIT_4;

        mysqli_query($db, $sql);
        if (mysqli_affected_rows($db) == 0) 
        {
            $sql =  "insert into diet_audits set ". 
                    " IDuser    = ".$user.
                    ",IDperiod  = ".$period.
                    ",IDproduct = ".$row['id'].
                    ",IDmix     = 0".
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
                    ",audit     = ".AUDIT_4;

            if (!mysqli_query($db, $sql))
            {
                echo mysqli_error($db);
            }
        }

        $tot_1 += $kcal;
    }
    printFooter();
    if ($tot_1 == 0) {

        echo "No tens cap registre de menjars per aquesta data";
        include './includes/googleFooter.inc.php';
        exit;
    }

    $real = array();
    $theoric = array();

    echo "<br>";
    echo "<input type='button' value=' ? ' onclick='javascript:explain(11)'>";
    echo "<br><br>";
    echo "<table class='dietcard'>".
            "<thead><tr>".
                "<th>Àpat</th>".
                "<th>Calories</th>".
                "<th>Percent</th>".
                "<th>Ajust</th>".
            "</tr></thead>";

    $sql =  "select au.*, dm.name name, dm.percent percent from diet_audits au ".
            "join diet_meals dm on dm.IDmeal = au.IDproduct ".
            " where IDuser    = ".$user.
            "   and IDperiod  = ".$period.
            "   and audit     = ".AUDIT_4;
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $percent = round(($row['kcal_1'] * 100) / $tot_1, 2);
        $diff = abs($percent - $row['percent']);
        switch ($diff) {
            case $diff <= 2:
                $color = "green";
                break;
            case $diff < 4:
                $color = "orange";
                break;
            default:
                $color = "red";
        }
        echo $rowStart.$row['name'].$newColNum.$row['kcal_1']." kcal".$newColNum.$percent." %".$newColNum."<span style='color:$color'>$diff</span>".$rowEnd;

        if ($row['percent'] > 0) {

            $real[] = $percent;
            $theoric[] = $row['percent'];
        }
    }
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

<!--div class="banner">En proves</div-->

<script>
    dr = document.getElementById('dateRange');
    dr.innerHTML = "<?php echo $_SESSION['dateRange'] ?>";

    // helpers

    const Utils = ChartUtils.init();

    // setup
    
    const data_1 = {
    labels: ['Desdejuni','Esmorzar','Dinar','Berenar','Sopar'],
    datasets: [
            {
                label: 'Percentatge',
                data: [<?php 
                    $c = 0;
                    foreach ($real as $key => $value) {
                        if ($c) echo ",";
                        echo $value;
                        $c += 1;
                    }
                ?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    const data_2 = {
    labels: ['Desdejuni','Esmorzar','Dinar','Berenar','Sopar'],
    datasets: [
            {
                label: 'Recomanat',
                data: [<?php 
                    $c = 0;
                    foreach ($theoric as $key => $value) {
                        if ($c) echo ",";
                        echo $value;
                        $c += 1;
                    }
                ?>],
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
                    text: 'Desglòs calòric'
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
                    text: 'Desglòs ideal'
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