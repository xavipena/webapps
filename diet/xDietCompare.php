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
    include "./includes/background.inc.php";
?>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
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
    $menuType = MENU_COMPARE;
    // save session for product box selector
    $_SESSION['menu'] = $menuType;
    include "./includes/dietMenu.inc.php";

    $user = "0";
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
            
            $user = $_SESSION['diet_user'];
        }
    }

    $c = 0;
    echo "<div class='container'>";
    $sql =  "select dc.IDproduct dprod, dp.name pname, pb.name bname ".
            "from diet_compare dc ".
            "join diet_products dp on dp.IDproduct = dc.Idproduct ".
            "join diet_product_brands pb on pb.IDbrand = dp.brand ".
            "where dc.IDuser = $user";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $c += 1;
        echo "<div><h2>".$row['pname']."</h2>De ".$row['bname']."<br><br>";
        echo "<div class='details-inline' id='comp_$c'></div>";
        echo "<script>GetDetailsEx(".$row['dprod'].",'1','comp_$c')</script>";
        echo "<input type='button' value=' Treu ' onclick='location.href=\"xDietCompare_2.php?prd=".$row['dprod']."\"'>";
        echo "</div>";
    }
    if ($c == 0) {

        echo "Selecciona productes de la Graella per comparar-los";
    }
    echo "</div>";

    include './includes/googleFooter.inc.php';
?>