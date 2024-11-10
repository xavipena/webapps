<?php
/*
    Submenus
    set this variebles to activate properly
    -> submenuType to activate and set the menu type
*/

// IF submenu
if (!empty($submenuType)) 
{
    // ------------------------
    // Make sure this exists
    // ------------------------
    if (empty($selectedSubMenuOption)) $selectedSubMenuOption = -1;

    echo "<div class='inner'>";
    if (($isIPhone || $isAndroid) && !$isTab) {

        echo "<ul class='menu'>";
    }
    else {
    
        echo "<ul class='threed-menu'>";
    }
    switch ($submenuType) {
        
        case SUBMENU_DISHES:

            MenuList($db, "22, 23, 24, 30, 31", $selectedSubMenuOption);
            break;

        case SUBMENU_PRODUCTS:

            MenuList($db, "29, 39, 40, 42, 45, 43, 47", $selectedSubMenuOption);
            break;
    }
    echo "</ul></div>";
}