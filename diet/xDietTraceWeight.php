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


    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";

    $initial = [0,0,0,0,0,0,0,0,0,0];
    $wished = [0,0,0,0,0,0,0,0,0,0];
    $real = [0,0,0,0,0,0,0,0,0,0];

    $sql =  "select weight, lossKg from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    for ($i = 0; $i < 10; $i++)
    {
        $initial[$i] = $row['weight'];
        $wished[$i] = $row['weight'] - $row['lossKg'];
    }
    $margin = 2;
    $minWeight = $row['weight'] - $row['lossKg'] - $margin;
    $maxWeight = $row['weight'] + $margin;

    $i = 9;
    $sql =  "select date, weight from diet_user_data ".
            "where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." and weight > 0 ".
            " order by date desc limit 10";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $real[$i] = $row['weight'];
        $i -= 1;
        if ($i < 0) break;
    }
    if ($i) 
    {
        for ($a = $i; $a >= 0; $a--)
        {    
            $real[$a] = $initial[$a];
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
            label: 'Pes inicial',
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
        },{
            label: 'Pes desitjat',
            data: [
                <?php 
                $c = 0;
                foreach ($wished as $weight)
                {
                    if ($c) echo ",";
                    echo $weight;
                    $c += 1;
                }
                ?>
            ],
            fill: false,
            borderColor: 'rgb(12, 214, 29)',
            tension: 0.1
            
        },{
            label: 'Pes real',
            data: [
                <?php 
                $c = 0;
                foreach ($real as $weight)
                {
                    if ($c) echo ",";
                    echo $weight;
                    $c += 1;
                }
                ?>
            ],
            fill: false,
            borderColor: 'rgb(245, 92, 92)',
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
                    },
                    suggestedMin: <?php echo $minWeight ?>,
                    suggestedMax: <?php echo $maxWeight ?>
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
