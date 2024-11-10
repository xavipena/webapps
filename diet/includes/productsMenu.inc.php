<?php
/**
 *
 * @author xavipena
 */
include __DIR__."/dietMenuFunctions.inc.php";

echo "<div class='inner'>";
if (($isIPhone || $isAndroid) && !$isTab) {

    echo "<ul class='menu'>";
}
else {

    echo "<ul class='threed-menu'>";
}
// Make sure this exists
if (empty($selectedProductMenuOption)) $selectedProductMenuOption = -1;

MenuList($db, "25, 26, 27, 28, 18, 41", $selectedProductMenuOption);
echo "</ul></div>";

include __DIR__."/dietSubMenus.inc.php";

// FatSecret API
echo "<div class='inner'>";
echo "  <div class='container vcenter'>";
echo "      <form id='searchf' method='get' action='xDietProductSearch.php'>".
     "          <label for='searchBox'>Cerca:&nbsp;</label>".
     "          <input type='hidden' value='$searchSource' name='searchID'>".
     "          <input type='text' value='' id='searchBox' name='searchBox' placeholder='Patata'>".
     "      </form>";
echo "      <a href='javascript:FS_searchProduct(\"$element\")'><img src='./images/Fatsecret_logo.png'></a>";
echo "  </div>";
echo "</div>";

echo "<hr>";
