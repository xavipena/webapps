<?php
/**
 *
 * @author xavipena
 * 
 * ------------------------
 * local menu
 * ------------------------
 */
$delimiter = "";
include __DIR__."/dietMenuFunctions.inc.php";

// ------------------------
// Make sure this exists
// ------------------------
if (empty($selectedMenuOption)) $selectedMenuOption = -1;
if (empty($menuType))
{
    $menuType = 0;
}

echo "<div class='inner'>";
if (($isIPhone || $isAndroid) && !$isTab) {

    echo "<ul class='menu'>";
}
else {

    echo "<ul class='threed-menu'>";
}
MenuList($db, "0", -1); // Inici
switch ($menuType)
{
    case MENU_CALCULATE:

        MenuList($db, "4, 5, 6, 51, 52, 14, 3", $selectedMenuOption);
        break;

    case MENU_DIET:
        
        MenuList($db, "1", $selectedMenuOption);
        break;

    case MENU_SELECTION:
    
        MenuList($db, "7, 8, 1", $selectedMenuOption);
        break;

    case MENU_TOPICS:
    
        MenuList($db, "13, 15", $selectedMenuOption);
        break;

    case MENU_PRODUCT:

        MenuList($db, "7, 8", $selectedMenuOption);
        break;

    case MENU_COMPARE:

        MenuList($db, "27", -1);
        break;
}

// ------------------------
// New item, depending on option
// ------------------------
switch ($sourceTable) {

    case "diet_products":

        MenuList($db, "18", $selectedMenuOption);
        break;

    case "diet_user_meals":

        MenuList($db, "19", $selectedMenuOption);
        break;

    case "diet_dishes":

        MenuList($db, "20", $selectedMenuOption);
        break;
}

// ------------------------
// User
// ------------------------
$mList = "2";
if (isset($_SESSION['diet_user'])) 
{
    if ($_SESSION['diet_user'] != "") 
    {
        switch ($menuType)
        {
            case MENU_DIET:
            
                // Apat / Gràfics / Análisi
                $mList .= ", 3, 21, 34";
                break;
    
            case MENU_SELECTION:
            
                // Apat 
                $mList .= ", 3";
                break;
        }
    }
}
MenuList($db, $mList, $selectedMenuOption);
echo "</ul></div>";

include __DIR__."/dietSubMenus.inc.php";
//echo "<hr>";
