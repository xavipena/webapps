<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    $useCharts = "Y";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';

    $pweek = empty($clean['week']) ? 0 : $clean['week'];
?>
<style>
    body {
        color: lightgray; 
    	background: transparent;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";	
        font-size: 14px;
    }
</style>
</head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";

//--- functions -------------------- 


//--- new content -------------------- 

//echo "<div class='dietPageContainer'>";

// get dates from current period
$sql =  "select beginDate, endDate from diet_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
$result = mysqli_query($db, $sql);
$dts = mysqli_fetch_array($result);

// count days in period
$sql =  "select count(distinct(date)) from diet_user_meals ".
        "where IDuser = ".$_SESSION['diet_user']." and date between '".$dts['beginDate']."' and '".$dts['endDate']."' ";
$result = mysqli_query($db, $sql);
$cnt = mysqli_fetch_array($result)[0]; 

// se array doimension
$week =  array_fill(0, $cnt, 0);

// Get data to represent
$i = 0;
$sql =  "select date, sum(calories * quantity) as cals from diet_user_meals ".
        "where IDuser = ".$_SESSION['diet_user']." and date between '".$dts['beginDate']."' and '".$dts['endDate']."' ".
        "group by date order by date";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $week[$i] = $row['cals'];
    $i += 1;
}

$sql = "select * from diet_users where IDuser = ".$_SESSION['diet_user'];
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {
    
    $recommended = $row['recommended'];
    $lossDiet = $row['lossDiet'];
}
echo "<div style='width: 600px'><canvas id='chart_1'></canvas></div>";
echo "</div>";
?>

<script>
    // helpers
    //const Utils = ChartUtils.init();

    const labels_1 = [
        <?php
        for ($i = $cnt; $i > 0; $i--) {

            if ($i < $cnt) echo ",";
            echo "'$i'";
        }
        ?>
    ];

    const data_1 = {
        labels: labels_1,
        datasets: [{
            label: 'Kcal en el periode',
            data: [
                <?php 
                $c = 0;
                foreach ($week as $day)
                {
                    if ($c) echo ",";
                    echo $day;
                    $c += 1;
                }
                ?>
            ],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        },{
            label: 'Límit per perdre pes',
            data: [
                <?php 
                $c = 0;
                foreach ($week as $day)
                {
                    if ($c) echo ",";
                    echo $lossDiet;
                    $c += 1;
                }
                ?>
            ],
            fill: false,
            borderColor: 'rgb(12, 214, 29)',
            tension: 0.1
            
        },{
            label: 'Colories racomanades per dia',
            data: [
                <?php 
                $c = 0;
                foreach ($week as $day)
                {
                    if ($c) echo ",";
                    echo $recommended;
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
