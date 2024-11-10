<?php
    session_start();
    $page = "diet";
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];

    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        .greenTitle {
            color: greenyellow;
            font-weight: bold;
        }
        .orange {
            color: orange;
        }
        .red {
            color: red;
        }
        .gray {
            color: gray;
        }
    </style>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    function printHeader() {

        echo "<div id='dateRange' class='greenTitle'>".$_SESSION['dateRange']."</div>";
        echo "<br>";
        echo "<table class='dietcard'>".
             "<thead><tr>".
                    "<th>Producte</th>".
                    "<th>Qtat</th>".
                    "<th>Kcal</th>".
                    "<th>Carbohid.</th>".
                    "<th>Kcal</th>".
                    "<th>Proteïnes</th>".
                    "<th>Kcal</th>".
                    "<th>Greixos</th>".
                    "<th>Kcal</th>".
                    "<th>Fibra</th>".
                    "<th>Kcal</th>".
                    "<th>Alcohol</th>".
                    "<th>Kcal</th>".
            "</tr></thead>";
    }

    function PrintFooter() {

        echo "<tfoot>";
        echo "<tr>";
        echo "    <td></td>";
        echo "    <td></td>";
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

    function PrintCell($data) {

        $redLimit = 1000;
        $orangeLimit = 500;

        $class = "number";
        if ($data > $redLimit) $class .= " red";
        else if ($data > $orangeLimit) $class .= " orange";
        else if ($data == 0) $class .= " gray";
        return "<td class='$class'>$data</td>";
    }

    function PrintGrams($data) {

        $class = "number";
        if ($data == 0) $class .= " gray";
        return "<td class='$class'>$data g</td>";
    }

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    $user = "0";
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
            
            $user = $_SESSION['diet_user'];
        }
    }
    if ($user == "0") echo "No s'ha assignat cap usuari";

    $toDate = date('Y-m-d');
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

    $numCols = 6;
    $numDays = 0;
    $auxDate = "";

    printHeader();

    $sql =  "select da.*, dp.IDproduct pID, dp.name as pname, dx.name xname from diet_audits da ".
            "left join diet_products dp on dp.IDproduct = da.IDproduct ".
            "left join diet_product_mix dx on dx.IDmix = da.IDmix ".
            "where IDuser = $user and audit = ".AUDIT_1." ".
            "order by pname";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        echo "<tr>".
             "<td><a href='xDietProduct_3.php?dtProd=".$row['pID']."'>".$row['pname'].$row['xname']."</a></td>".
             "<td class='number'>".$row['quantity']."</td>".
             PrintCell($row['kcal_1']).
             PrintGrams($row['grams_2']).
             PrintCell($row['kcal_2']).
             PrintGrams($row['grams_3']).
             PrintCell($row['kcal_3']).
             PrintGrams($row['grams_4']).
             PrintCell($row['kcal_4']).
             PrintGrams($row['grams_5']).
             PrintCell($row['kcal_5']).
             PrintGrams($row['grams_6']).
             PrintCell($row['kcal_6']).
             "</tr>";

        $tot_1 += $row['kcal_1'];
        $tot_2 += $row['kcal_2'];
        $tot_3 += $row['kcal_3'];
        $tot_4 += $row['kcal_4'];
        $tot_5 += $row['kcal_5'];
        $tot_6 += $row['kcal_6'];

        $tot_22 += $row['grams_2'];
        $tot_32 += $row['grams_3'];
        $tot_42 += $row['grams_4'];
        $tot_52 += $row['grams_5'];
        $tot_62 += $row['grams_6'];
    }
    printFooter();
    include './includes/googleFooter.inc.php';
?>