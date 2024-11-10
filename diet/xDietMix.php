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
    <script>
        function SetNewType() 
        {
            d = document.getElementById("catSelect").value;
            location.href="xDietMix.php?cat=" + d;
        }

    </script>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $cat = empty($clean['cat']) ? 0 : $clean['cat'];
    
    //--- Settings ------------------------

    if (!empty($clean['mealSelect_v'])) $_SESSION['meal'] = $clean['mealSelect_v']; // sushi
    if (!empty($clean['mealSelect_w'])) $_SESSION['meal'] = $clean['mealSelect_w']; // picoteo

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    // --------------------------------
    // Mount the selector 
    // --------------------------------

    function CatSelector($db, $current)
    {
        $catSelector = "<select onchange='SetNewType()' id='catSelect' name='catSelect'>";
        //$catSelector .= "<option value='0'>Selecciona</option>";

        $sql ="select * from diet_mixes order by name";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) 
        {
            $selected = $row['IDmixcat'] == $current ? "selected" : ""; 
            $catSelector .= "<option $selected value='".$row['IDmixcat']."'>".$row['name']."</option>";
        }
        $catSelector .= "</select>";
        return $catSelector;
    }

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "Combinació de ".CatSelector($db, $cat);
    if ($cat == 0) 
    {
        // No more
        include './includes/googleFooter.inc.php';
        exit;
    }
    $sql = "select name from diet_mixes where IDmixcat = $cat";
    $result = mysqli_query($db, $sql);
    $catname = mysqli_fetch_array($result)[0]; 

    // --------------------------------
    // Selected data table
    // --------------------------------

    echo "<br><br>";
    echo "<table class='dietcard'>";
    echo "<caption>Combinació de $catname</caption>";
    echo "<thead><tr><th>Treu</th>".
                "<th>Producte</th>".
                "<th>Calories</th>".
                "<th>Greixos</th>".
                "<th>Carbohidrats</th>".
                "<th>Proteïnes</th>".
                "<th>Quantitat</th>".
                "<th>+</th>".
                "<th>-</th>".
                "</tr></thead>";

    $numCols = 4;
    $pvalue = array(0.0,0.0,0.0,0.0,0.0);
    $ptotal = array(0.0,0.0,0.0,0.0,0.0);
    $qtyTotal = 0;

    $sql =  "select dd.IDmix prod, dd.quantity qty, ".
                  " dp.name pname, energy, fat, carbohydrate, protein ".
            "from diet_user_mix dd ".
            "join diet_product_mix dp on dp.IDmix = dd.IDmix ";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    { 
        $pvalue[0] = round($row['qty'] * $row['energy'], 2);
        $pvalue[1] = round($row['qty'] * $row['fat'], 2);
        $pvalue[2] = round($row['qty'] * $row['carbohydrate'], 2);
        $pvalue[3] = round($row['qty'] * $row['protein'], 2);
            
        echo $rowStart."[<a href='xDietMixDel.php?cat=$cat&prd=".$row['prod']."'>Treu</a>]";
        echo $newCol.$row['pname']."</td>";
        for ($c = 0; $c < $numCols; $c++) {

            printf("<td class='number'>%.2f</td>",$pvalue[$c]);
            $ptotal[$c] += $pvalue[$c];
        }
        $qtyTotal += $row['qty'];
        echo $newColNum.$row['qty'].
             $newCol."[<a href='xDietMixQty.php?cat=$cat&mix=".$row['prod']."&act=add'>+</a>]".
             $newCol."[<a href='xDietMixQty.php?cat=$cat&mix=".$row['prod']."&act=sub'>-</a>]";
    }

    echo "<tfoot>";
    echo $rowStart;
    echo "    </td><td></td>";
    for ($c = 0; $c < $numCols; $c++) {

        printf("<td class='number'>%.2f</td>",$ptotal[$c]);
    }
    echo "<td class='number'>".$qtyTotal."</td>";
    echo "</tr>";
    echo "</tfoot>";
    echo "</table>";

    echo "<input type='button' value='Afegeix un producte' onclick='location.href=\"xDietMixAdd.php?cat=$cat\"'>&nbsp;";
    echo "<input type='button' value='Afegeix a la meva selecció' onclick='location.href=\"xDietMix_2.php\"'>&nbsp;";
    echo "<input type='button' value='Neteja' onclick='location.href=\"xDietMix_3.php?cat=$cat\"'>";
    include './includes/googleFooter.inc.php';
?>