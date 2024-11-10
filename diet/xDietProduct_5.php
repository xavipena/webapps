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
    
    $prd = empty($clean['prd']) ? 0 : $clean['prd'];
    
    //--- Settings ------------------------

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

    if ($prd > 0) {

        $sql = "select IDproduct, description, IG from diet_products where IDproduct = $prd";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        $name = $row['description'];
        echo "<h2>$name</h2>"; 

        echo "<form id='fig' method='post' action='xDietProduct_7.php'>";
        echo "<input type='hidden' name='c0' value='$prd'>";
        echo "IG: <input type='number' name='c1' class='number-input' value='".$row['IG']."''>";
        echo "&nbsp;<input type='submit' value='Envia'>";
        echo "</form><a href='https://glycemic-index.net/es/'>Es pot mirar aquí</a><br><br>";
    }
    else echo "** Producte no trobat<br>";
    echo "<spn class='smallText'>Unitats en mg/100g</span><hr color='gray'>";

    echo "<form id='fr' method='post' action='xDietProduct_6.php'>";
    echo "<input type='hidden' name='c0' value='$prd'>";
    echo "<table>$rowStart";

    $sql = "select count(*) from diet_components";
    $result = mysqli_query($db, $sql);
    $cnt = round(mysqli_fetch_array($result)[0] / 2, 0); 

    $c = 0;
    echo "<table>$rowStart";
    $sql = "select IDcomponent, name from diet_components";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        if ($c == $cnt) {
            
            // start right column
            echo "$rowEnd</table>";
            echo $newCol;
            echo "&nbsp;&nbsp;";
            echo $newCol;
            echo "<table>$rowStart";
        }
        $val = 0;
        $sql = "select * from diet_product_composition where IDproduct = $prd and IDcomponent = ".$row['IDcomponent'];
        $res = mysqli_query($db, $sql);
        if ($cmp = mysqli_fetch_array($res))
        {
            $val = $cmp['quantity'];
        } 
        echo "<tr><td valign='middle'>".$row['name'].$newCol."<input class='number-input' type='number' value='$val' name='c".$row['IDcomponent']."' step='any'>".$rowEnd;
        $c += 1;
    }
    echo "$rowEnd</table>"; // inner
    echo "$rowEnd</table>"; // outer
    if ($prd != 0) {

        echo "<input type='submit' value='Envia'>&nbsp;";
    }
    echo "<input type='button' value='Finalitza' onclick='location.href=\"xDietProductFull.php?prd=$prd\"'>";
    echo "</form>";

    include './includes/googleFooter.inc.php';
?>