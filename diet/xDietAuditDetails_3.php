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
        .yellow {
            color: lightpink;
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
    
    function printHeader($col) {

        switch ($col) {

            case 1:
            
                echo "<div id='dateRange' class='greenTitle'>".$_SESSION['dateRange']."</div>";
                break;

            case 2:

                echo "<div id='topTen' class='greenTitle'>Top ten</div>";
                break;
        }
        echo "<br>";            
        echo "<table class='dietcard'>".
             "<thead><tr>".
                "<th>Producte</th>".
                "<th>Qtat</th>".
                "<th>Kcal</th>".
                "</tr></thead>";
    }

    function PrintFooter() {

        echo "<tfoot>";
        echo "<tr>";
        echo "    <td></td>";
        echo "    <td></td>";
        echo "<td class='number'>".$GLOBALS['tot_1']."</td>";
        echo "</tr>";
        echo "</tfoot>";
        echo "</table>";
    }

    function PrintLine($data) {

        $redLimit = 2000;
        $orangeLimit = 1000;
        $yellowLimit = 500;

        $class = "number";
        if ($data > $redLimit) $class .= " red";
        else if ($data > $orangeLimit) $class .= " orange";
        else if ($data > $yellowLimit) $class .= " yellow";
        else if ($data == 0) $class .= " gray";
        return "<td class='$class'>$data</td>";
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
    $numCols = 6;
    $numDays = 0;
    $auxDate = "";

    echo "<table>".$rowStart;

    printHeader(1);

    $sql =  "select da.*, dp.name as pname, dx.name xname from diet_audits da ".
            "left join diet_products dp on dp.IDproduct = da.IDproduct ".
            "left join diet_product_mix dx on dx.IDmix = da.IDmix ".
            "where IDuser = $user and audit = 3";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        echo "<tr>".
             "<td>".$row['pname'].$row['xname']."</td>".
             "<td class='number'>".$row['quantity']."</td>".
             PrintLine($row['kcal_1']).
             "</tr>";

        $tot_1 += $row['kcal_1'];
    }
    printFooter();

    echo $newCol."&nbsp;".$newCol;

    // Prepare data for nomalization
    // Get max and min
    // then n = (x - min) / (max - min)
    /*
    $sql =  "select max(kcal_1) as max, min(kcal_1) as min from diet_audits ".
            "where IDuser = $user and audit = 3 limit 10";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    $max = $row['max'];
    $min = $row['min'];
    */
    printHeader(2);
    $sql =  "select da.*, dp.name as pname, dx.name xname from diet_audits da ".
            "left join diet_products dp on dp.IDproduct = da.IDproduct ".
            "left join diet_product_mix dx on dx.IDmix = da.IDmix ".
            "where IDuser = $user and audit = 3 order by kcal_1 desc limit 10";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        //$nor = ($row['kcal_1'] - $min) / ($max - $min);
        echo "<tr>".
            "<td>".$row['pname'].$row['xname']."</td>".
            "<td class='number'>".$row['quantity']."</td>".
            PrintLine($row['kcal_1']).
            //"<td class='number'>$nor</td>".
            "</tr>";

        $tot_1 += $row['kcal_1'];
    }
    printFooter();

    echo $rowEnd."</table>";

    include './includes/googleFooter.inc.php';
?>