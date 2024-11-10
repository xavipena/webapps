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
        function SetNewType(p) 
        {
            if (p == "x" || p == "y") return;
            d = document.getElementById("catSelect_" + p).value;
            location.href="xDietMaintenance_12.php?prd=" + p + "&cat=" + d;
        }
    </script>
</head>
<body>
<?php 
    //--- settings -_---------------------

    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_user_meals";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ----------------------
    
    include "./includes/dietFunctions.inc.php";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_DIET;
    include "./includes/dietMenu.inc.php";
    echo DietSettings($db, "2");
?>
    <h3>Productes</h3>
    <h3>Assignar grup d'aliment</h3>
    <table class="dietcard">
        <thead>
            <caption>Productes</caption>
            <tr>
                <th>Producte</th>
                <th>Categoria</th>
                <th>Grup</th>
            </tr>
        </thead>
<?php
    $total = 0;
    $salt = 0;
    $sugar = 0;

// --------------------------------
// Mount the category selector by product
// --------------------------------

function CatSelector($db, $prd, $current)
{
    $catSelector = "<select onchange='SetNewType($prd)' id='catSelect_$prd' name='catSelect_$prd'>";
    $catSelector .= "<option value='0'>Sense assignar</option>";

    $sql ="select * from diet_product_categories order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $selected = $row['IDcat'] == $current ? "selected" : ""; 
        $catSelector .= "<option $selected value='".$row['IDcat']."'>".$row['name']."</option>";
    }
    $catSelector .= "</select>";
    return $catSelector;
}

// --------------------------------
// Display data
// --------------------------------

$aux = "";
$rows = 0;
$sql =  "select IDproduct, IDcat, name from diet_products where status = 'A' order by name";
$result = mysqli_query($db, $sql);
while ($prd = mysqli_fetch_array($result)) 
{
    if ($_SESSION['unassigned'] == "N") $show = true; 
    else $show = $_SESSION['unassigned'] == "Y" && $prd['IDcat'] == 0 ? true : false;
    if ($show)
    {
        echo "<tr><td class='number'>".$prd['IDproduct'].
                $newColNum.$prd['name'].
                $newCol.CatSelector($db, $prd['IDproduct'], $prd['IDcat']).$rowEnd;
    }
}
echo "</table>";

include './includes/googleFooter.inc.php';
