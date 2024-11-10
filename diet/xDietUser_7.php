<?php
    session_start();
    $runner_id ="";
    $page = "diet";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];

    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        label {
            display: inline-block;
            width: 100px;
            margin: 20px 10px 0px 0px;
            text-align: right;
        }
    </style>
</head>
<body>
<?php 
    //--- params ------------------------- 

    $action = empty($clean['op']) ? FORM_ADD : $clean['op'];

    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_users";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    function FormPeriod($db, $action) {

        $descr = "";
        $bdate = "";
        $edate = "";
        $period = 0;

        if ($action == FORM_EDIT) {

            $sql = "select * from diet_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);

            $descr = $row['description'];
            $bdate = $row['beginDate'];
            $edate = $row['endDate'];
            $period = $_SESSION['diet_period'];
        }
        $output  = "<form id='dietPer' method='post' action='xDietUser_8.php'>";
        $output .= "<input type='hidden' name='op' value='$action'>";
        $output .= "<input type='hidden' name='dtPeri' value='$period'>";
        $output .= "<fieldset>";
        $output .= "<legend>Periode</legend>";
        $output .= "<label for='dtDesc'>Nom: </label>";
        $output .= "<input type='text' id='dtDesc' name='dtDesc' value='$descr' size='60' required>";

        $output .= "<br><label for='dtFrom'>Des de: </label>";
        $output .= "<input type='date' id='dtFrom' name='dtFrom' value='$bdate'>";
        
        $output .= "<br><label for='dtTo'>Fins: </label>";
        $output .= "<input type='date' id='dtTo' name='dtTo' value='$edate'>";
        
        $output .= "<br><br><input type='submit' value=' Envia '>";
        $output .= "</fieldset>";
        $output .= "</form>";
        return $output;
    }

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 2;
    
    echo "<h2>Nou periode</h2>";
    
    echo FormPeriod($db, $action);
    
    echo "<h2>Llista de periodes</h2>";
    echo "<table class='dietcard'>";
    echo "<thead>";
    echo "<tr><th>Descripció</th>".
             "<th>Data inici</th>".
             "<th>Data fi</th>".
             "<th>Dies de dieta</th>".
             "<th>Complets</th></tr>";
    echo "</thead>";

    $sql = "select * from diet_periods where IDuser = ".$_SESSION['diet_user'];
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {

        $sql = "select count(distinct(date)) from diet_user_meals where IDuser = ".$_SESSION['diet_user']." and date between ".$row['beginDate']." and ".$row['endDate'];
        $res = mysqli_query($db, $sql);
        $cnt = mysqli_fetch_array($res)[0];
    
        $begin = strtotime($row['beginDate']);
        $formatted_1 = date('d-m-Y', $begin);

        $end   = strtotime($row['endDate']);
        $formatted_2 = date('d-m-Y', $end);

        $datediff = $end - $begin;
        $days = round($datediff / (60 * 60 * 24)) + 1;

        echo $rowStart.$row['description'].$newCol.$formatted_1.$newCol.$formatted_2.$newColNum.$days.$newColNum.$cnt.$rowEnd;
    }
    echo "</table>";

    include './includes/googleFooter.inc.php';
?>