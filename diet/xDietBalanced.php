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
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
    //--- Settings ------------------------

    $user = "0";
    $period = 0;
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
            
            $user = $_SESSION['diet_user'];
            $period = $_SESSION['diet_period'];
        }
    }
    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    // get monday from last week as limitDate
    $week = date("w");
    $today = 6 + $week;
    $limitDate = date('Y-m-d', strtotime('-'.$today.' day'));
    $endDate = date('Y-m-d', strtotime('-'.$week.' day'));

    $sql =  "select count(*) from diet_product_categories ";
    $res = mysqli_query($db, $sql);
    $size_of_the_array = mysqli_fetch_array($res)[0] + 1;

    $aux = 0;
    $qty = array_fill(0, $size_of_the_array, 0);
    $sql =  "select dum.IDproduct pID, dp.IDcat pCat, dp.name pname, dum.quantity qty, dpc.units unit ".
            "from diet_user_meals dum ".
            "join diet_products dp ".
            "  on dp.IDproduct = dum.IDproduct ".
            "left join diet_product_categories dpc ".
            "  on dpc.IDcat = dp.IDcat ".
            "where dum.IDuser = $user and dum.IDperiod = $period and date >= '$limitDate' and date < '".$endDate."'".
            "order by dum.IDproduct, dp.IDcat";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $grams = 1;
        if ($row['unit'] == "kg")
        {
            $sql =  "select * from diet_product_data where IDproduct = ".$row['pID']." and unit = 'Ration'";
            $res = mysqli_query($db, $sql);
            if ($dat = mysqli_fetch_array($res))
            {
                $grams = $dat['grams'];
            }
        }
        $qty[$row['pCat']] += ($row['qty'] * $grams);    
    }

    echo "Valors setmanals de la setmana ".$limitDate." a ".$endDate."<br><br>";
    echo "<table class='dietcard'>";
    $i = 0;
    foreach ($qty as $q) 
    {
        $sql =  "select * from diet_product_categories where IDcat = $i";
        $res = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($res)) 
        {
            $un = "u";
            if ($row['units'] == "kg")
            {
                $un = "g";
            }
            echo $rowStart.$row['IDcat'].$newCol.$row['name'].$newColNum.$q.$un.$newCol.$row['recommended'].$newCol.$row['units'].$rowEnd;
        }
        else
        {
            echo $rowStart."0".$newCol."Sense assignar".$newCol.$q.$newCol.$rowEnd;
        }
        $i += 1;
    }
    echo "</table>";

    include './includes/googleFooter.inc.php';
?>