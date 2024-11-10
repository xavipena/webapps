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
    include "./includes/dietFunctions.inc.php";
?>
</head>
<body>
<?php 
    $_SESSION['meal'] = empty($clean['mealSelect_x']) ? "" : $clean['mealSelect_x'];
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_products";
    $searchSource = "lst";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 


    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    //include "./includes/dietMenu.inc.php";
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    echo "<h2>Sucre</h2>";
    echo "<ul class='menu'>";
    echo "<li><a href='xDietProductsFiltered.php?data=4&mode=1'>Alt en sucre</a></li>";
    echo "<li><a href='xDietProductsFiltered.php?data=4&mode=2'>Baix en sucre</a></li>";
    echo "<li><a href='xDietProductsFiltered.php?data=4&mode=3'>Sense sucre</a></li>";
    echo "</ul>";

    echo "<h2>Sal</h2>";
    echo "<ul class='menu'>";
    echo "<li><a href='xDietProductsFiltered.php?data=7&mode=1'>Alt en sal</a></li>";
    echo "<li><a href='xDietProductsFiltered.php?data=7&mode=2'>Baix en sal</av</li>";
    echo "<li><a href='xDietProductsFiltered.php?data=7&mode=3'>Sense sal</av</li>";
    echo "</ul>";

    include './includes/googleFooter.inc.php';
?>