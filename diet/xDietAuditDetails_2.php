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
                    "<th>Sucre</th>".
                    "<th>Sal</th>".
                    "<th>Alcohol</th>".
                "</tr></thead>";
        }

    function PrintFooter() {

        echo "<tfoot>";
        echo "<tr>";
        echo "    <td></td>";
        echo "    <td></td>";
        echo "<td class='number'>".$GLOBALS['tot_1']." g</td>";
        echo "<td class='number'>".number_format((float)$GLOBALS['tot_2'], 2, '.', '')." g</td>";
        echo "<td class='number'>".number_format((float)$GLOBALS['tot_3'], 2, '.', '')." g</td>";
        echo "</tr>";
        echo "</tfoot>";
        echo "</table>";
    }

    function PrintLine($qty, $data, $id) {

        switch ($id) {
            case 1:
                // sugar
                $redLimit = $GLOBALS['sugarLimit'];
                $orangeLimit = $GLOBALS['sugarLimit'] / 2;
                break;
            case 2:
                // salt
                $redLimit = 1;
                $orangeLimit = 0.9;
                break;
            case 3:
                // alcohol
                $redLimit = 10;
                $orangeLimit = 10;
                $qty = 1; // invariant
                break;
        }

        $data = $data / $qty;
        $class = "number";
        if ($data > $redLimit) $class .= " red";
        else if ($data > $orangeLimit) $class .= " orange";
        else if ($data == 0) $class .= " gray";

        return "<td class='$class'>".number_format((float)$data, 2, '.', '')."</td>";
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

    $numCols = 6;
    $numDays = 0;

    printHeader();

    $sql =  "select da.*, dp.name as pname, dx.name xname from diet_audits da ".
            "left join diet_products dp on dp.IDproduct = da.IDproduct ".
            "left join diet_product_mix dx on dx.IDmix = da.IDmix ".
            "where IDuser = $user and audit = 2";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        echo "<tr>".
             "<td>".$row['pname'].$row['xname']."</td>".
             "<td class='number'>".$row['quantity']."</td>".
             PrintLine($row['quantity'], $row['grams_1'], 1).
             PrintLine($row['quantity'], $row['grams_2'], 2).
             PrintLine($row['quantity'], $row['grams_3'], 3).
             "</tr>";

        $tot_1 += $row['grams_1'];
        $tot_2 += $row['grams_2'];
        if ($row['grams_3'] > $tot_3) $tot_3 = $row['grams_3']; // Max
    }
    printFooter();
    include './includes/googleFooter.inc.php';
?>