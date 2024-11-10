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
    $_SESSION['meal'] = empty($clean['mealSelect_x']) ? "" : $clean['mealSelect_x'];
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_products";
    $searchSource = "txt";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    function printTxtProduct($row) {
        
        $rowStart = $GLOBALS['rowStart'];
        $rowEnd = $GLOBALS['rowEnd'];
        $newCol = $GLOBALS['newCol'];

        echo "<tr><td class='number'>";
        echo $row['pID']."&nbsp;$newCol<a href='xDietProductFull.php?prd=".$row['pID']."'>".$row['pname']."</a>";
        if (isset($_SESSION['diet_user'])) 
        {
            if ($_SESSION['diet_user'] != "") 
            {
                echo "$newCol<a href='xDietSelectionAdd.php?prd=".$row['pID']."'>Selecciona</a>";
            }
        }
        echo $newCol.$row['bname'];
        echo $newCol.$row['pdesc'];
        echo $rowEnd;
    }
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $submenuType = SUBMENU_PRODUCTS;
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<table class='dietcard'>";
    echo "<thead>".
        "<th>ID</th>".
        "<th>Nom</th>".
        "<th></th>".
        "<th>Marca</th>".
        "<th>Descipció</th>".
        "</thead>";
    $c = 0;
    $columns = 3;
    $sql =  "select p.IDproduct pID, p.name pname, p.short pdesc, b.name bname from diet_products p ".
            "join diet_product_brands b on b.IDbrand = p.brand ".
            "where p.status = 'A' ".
            "order by p.name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        printTxtProduct($row);
        $c += 1;
    }
    echo "</table><br>";
    $c += 1;
    echo $c." productes";

    include './includes/googleFooter.inc.php';
?>