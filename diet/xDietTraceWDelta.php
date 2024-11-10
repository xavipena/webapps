<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
?>
     <script type="text/javascript" src="../js/chart.min.js"></script>
</head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";

//--- functions -------------------- 

include "./includes/dietFunctions.inc.php";

function CalcKgs($tot, $dietDate) {
    
    $calsNeeded =  $GLOBALS['diet_recom'] - $GLOBALS['diet_limit'];
    $calsToday  = $tot - $GLOBALS['burned'];
    $net = $calsToday - $calsNeeded;
    $gainLoss = $net;
    $kgs = 0;

    if ($GLOBALS['diet_target'] == 'A') {  // aprimar-se
        
        $kgs = round($gainLoss / CALS_PER_KG, 3);
    }
    return $kgs;
}

//--- new content -------------------- 

//echo "<div class='dietPageContainer'>";

$initial = [0,0,0,0,0,0,0,0,0,0];

$i = 9;
$tot = 0;
$auxDate = "";
$user    = $_SESSION['diet_user'];
$period  = $_SESSION['diet_period'];
$toDate  = date('Y-m-d');

$sql = "select * from diet_users where Iduser = ".$user;
$res = mysqli_query($db, $sql);
$usr = mysqli_fetch_array($res);

$calsNeeded =  $usr['recommended'] - $usr['limited'];
$diet_recom = $usr['recommended'];
$diet_limit = $usr['limited'];
$diet_target = $usr['target'];

// --------------------------------------------------

$burned = 0;
$auxDate = "";
$tot = 0;

$sql =  "select date, weight from diet_user_data ".
        "where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." and weight > 0 ".
        " order by date desc limit 10";
$res = mysqli_query($db, $sql);
while ($day = mysqli_fetch_array($res)) {

    $sql =  "select um.IDproduct pID, um.date date, um.IDmeal id, m.name mname, p.name pname, x.name xname, ".
            "   um.quantity qty, um.calories cal, d.salt dsalt, d.sugar dsugar, x.energy xcal ".
            "from diet_user_meals um ".
            "join diet_meals m on m.IDmeal = um.IDmeal ".
            "left join diet_products p on p.IDproduct = um.IDproduct ".
            "left join diet_product_data d on d.IDproduct = um.IDproduct and d.unit = 'Ration' ".
            "left join diet_product_mix x on x.IDmix = um.IDmix ".
            "where um.Iduser = ".$user. " and um.date = '".$day['date']."'";

    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {
        
        if ($auxDate != $row['date']) {

            // day separation
            if ($auxDate != "") {

                $sql =  "select burned ".
                        "from diet_user_data ".
                        "where Iduser = $user and IDperiod = $period and date = '".$auxDate."'";
                $res = mysqli_query($db, $sql);
                while ($bur = mysqli_fetch_array($res)) {
                
                    $burned = $bur['burned'];
                }
                $initial[$i] = CalcKgs($tot, $auxDate);
                
                //echo $initial[$i]." kg per $auxDate<br>";
                $i -= 1;
                if ($i < 0) break;
                $tot = 0;
            }
            $auxDate = $row['date'];
        }
        $tot += $row['qty'] * $row['cal'];
    }
}

// --------------------------------------------------
// Fill to end
if ($i) 
{
    for ($a = $i; $a >= 0; $a--)
    {    
        $initial[$a] = $initial[$i];
    }
}
?>

<div style='width: 600px'><canvas id='chart_1'></canvas></div>
</div>
<script>
    // helpers
    //const Utils = ChartUtils.init();

    const labels_1 = ['10', '9', '8', '7', '6', '5', '4','3','2','1'];

    const data_1 = {
        labels: labels_1,
        datasets: [{
            label: 'Increments de pes',
            data: [
                <?php 
                $c = 0;
                foreach ($initial as $weight)
                {
                    if ($c) echo ",";
                    echo $weight;
                    $c += 1;
                }
                ?>
            ],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    };
    const config_1 = {
        type: 'line',
        data: data_1,
        options: {
            scales: {
                x: {
                    grid: {
                        color: '#7b7b7b'
                    }
                },
                y: {
                    grid: {
                        color: '#7b7b7b'
                    }
                }
            }
        }
    };

    // rendering

    const Chart_1 = new Chart(
        document.getElementById('chart_1'),
        config_1
    );
</script>
