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

    $server = "https://diaridigital.net";
?>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    $mode = empty($clean['mode']) ? "" : $clean['mode'];
    
    //--- Settings --------------------- 
    
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_products";
    $searchSource = "prd";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    //include "./includes/dietMenu.inc.php";
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";
        
    // --------------------------------
    // Get type of data to search for
    // filter by data
    // --------------------------------
    $columns = array(
        "energy",
        "fat",
        "saturates",
        "carbohydrate",
        "sugar",
        "fiber",
        "protein",
        "salt"
    );
    $types = array(
        "",
        "alt en",
        "baix en",
        "zero"
    );
    $column = $columns[$data];
    $type = $types[$mode];
    echo "<h2>Cercant per '$column', de tipus '$type $column'</h2>";

    // --------------------------------
    // Set the limits
    // --------------------------------
    $limit = 0;
    $upperLimits = array(0,0,0,0,15,0,0,0.9); 
    $lowerLimits = array(0,0,0,0, 5,0,0,0.26); 
    switch ($mode)
    {
        case "1":
            $limit = $upperLimits[$data];
            break;
        case "2":
            $limit = $lowerLimits[$data];
            break;
        case "3":
            $limit = 0;
            break;
    }

    // --------------------------------
    // Print
    // --------------------------------
    echo "<div class='container'>";
    $c = 0;
    $aux = "";
    $aux2 = "";
    $sql =  "select dp.IDproduct pID, dp.name pname, dp.description pdesc, dpb.name bname, dp.food food ".
            "from diet_products dp ".
            "join diet_product_brands dpb ".
            "  on dp.brand = dpb.IDbrand ".
            "where dp.status = 'A' order by dp.name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $i = $row['pID'];

        $sql = "select $column from diet_product_data where IDproduct = $i and unit = 'Standard'";
        $res = mysqli_query($db, $sql);
        if ($row2 = mysqli_fetch_array($res)) 
        {
            // filter by mode
            $print = ($mode == "3" && $row2[0] == 0);
            if (!$print) $print = ($mode == "1" && $row2[0] > $limit);
            if (!$print) $print = ($mode == "2" && $row2[0] < $limit && $row2[0] > 0);
            if ($print)
            {
                echo PrintProduct("diet", $row, $column, $row2[0]);            
                $c += 1;
            }
        }
    }
    echo "</div>";
    echo "$c productes";

    include './includes/googleFooter.inc.php';
?>