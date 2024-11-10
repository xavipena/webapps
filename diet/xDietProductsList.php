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
    if (!empty($clean['mealSelect_x'])) $_SESSION['meal'] = $clean['mealSelect_x'];    
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_products";
    $searchSource = "lst";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 
        
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    //include "./includes/dietMenu.inc.php";
    $submenuType = SUBMENU_PRODUCTS;
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<div class='container'>";
    $c = 0;
    $columns = 3;
    $sql =  "select p.IDproduct pID, p.name pname, p.short pdesc, b.name bname from diet_products p ".
            "join diet_product_brands b on b.IDbrand = p.brand ".
            "where p.status = 'A' ".
            "order by p.name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        echo PrintProduct("diet", $row);
        $c += 1;
    }
    echo "</div>";
    $c += 1;
    echo $c." productes";

    include './includes/googleFooter.inc.php';
?>