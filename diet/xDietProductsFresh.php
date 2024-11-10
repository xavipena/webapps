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
    include "./includes/loader.inc.php";

    switch($clean['type']) {

        case 1:
            // Fresh
            $bimage = "pexels-rpphotography-693794";
            break;
        case 2:
            // Drinks
            $bimage = "pexels-chris-f-38966-1283219";
            break;
        case 3:
            // Salses
            $bimage = "pexels-tonyleong81-2092897";
            break;
        case 4:
            // Plats preparats
            $bimage = "pexels-valeriya-1639562";
            break;
        case 5:
            // Pans
            $bimage = "pexels-hermaion-137103";
            break;
    }
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 40%), url("./images/<?php echo $bimage?>.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<?php 
    //--- params ---------------------- 


    //--- settingd -------------------- 
    
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
    $submenuType = SUBMENU_PRODUCTS;
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<div class='container'>";
    $c = 0;
    $columns = 3;

    // ------------------------
    // Prepare SQL
    // ------------------------
    $sql =  "select p.IDproduct pID, p.name pname, p.short pdesc, b.name bname from diet_products p ".
            "join diet_product_brands b on b.IDbrand = p.brand ".
            "where p.status = 'A' ".
            "order by p.name";

    if (!empty($clean['type'])) {

        $sql =  "select p.IDproduct pID, p.name pname, p.short pdesc, b.name bname from diet_products p ";

        switch($clean['type']) {

            case 1:
                // Fresh
                $sql .= "join diet_product_brands b on b.IDbrand = p.brand and b.IDbrand = 14 ".
                        "where p.status = 'A' ".
                        "order by p.name";
                break;
            case 2:
                // Drinks
                $sql .= "join diet_product_brands b on b.IDbrand = p.brand ".
                        "where p.status = 'A' and food = 'Líquid' ".
                        "order by p.name";
                break;
            case 3:
                // Salses
                $sql .= "join diet_product_brands b on b.IDbrand = p.brand ".
                        "where p.status = 'A' and food = 'Salsa' ".
                        "order by p.name";
                break;
            case 4:
                // Plats preparats
                $sql .= "join diet_product_brands b on b.IDbrand = p.brand ".
                        "where p.status = 'A' and food = 'Preparat' ".
                        "order by p.name";
                break;
            case 5:
                // Pans
                $needle = "%pan%";
                $sql .= "left join diet_product_brands b on b.IDbrand = p.brand ".
                        "where p.status = 'A' and (p.name like '$needle' or p.description like '$needle') ".
                        "order by p.name";
                break;
        }
    }
    // ------------------------

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