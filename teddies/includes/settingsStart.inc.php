<?php
// ---------------------------------------------------------------
// Settings 
// Globals to check the device
// ---------------------------------------------------------------
define ('MENU_CALCULATE'    ,1);
define ('MENU_DIET'         ,2);
define ('MENU_TOPICS'       ,3);
define ('MENU_PRODUCT'      ,4);
define ('MENU_SELECTION'    ,5);
define ('MENU_COMPARE'      ,6);
 
define ('PAGE_DIET_MENU'    ,100);
define ('PAGE_DIET_TOPIC'   ,101);
define ('PAGE_DIET_GLOSSARY',102);
define ('PAGE_DIET_COVER'   ,103);
define ('PAGE_DIET_AUDIT'   ,104);

define ('SUBMENU_DISHES'    ,200);
define ('SUBMENU_PRODUCTS'  ,201);

// Show empty projects - no pending tasks
$setShowEmptyTasks = FALSE;

/* 
    ShowSQL($sql)
    params
        $sql    --> SQL
    return
        --> displays box
*/function ShowSQL($sql)
{
	echo "<div class='rounded'><br>";
	echo "&nbsp;&nbsp;".$sql."&nbsp;&nbsp;";
	echo "<br><br></div>";
}

function ShowWarning($sql)
{
	echo "<div class='warning'><br>";
	echo "&nbsp;&nbsp;".$sql."&nbsp;&nbsp;";
	echo "<br><br></div>";
}

function GetBlogDir($bl = 9)
{
	$sblog = "";
	if ($bl == 9) $bl = $GLOBALS['blog'];
    switch ($bl) {

        case 1:
            $sblog = "camiblog";
            break;

        case 2:
            $sblog = "cimsblog";
            break;

        case 3:
            $sblog = "karnak";
            break;
    }
    return $sblog;
}

// ------------------------------------------
// Globals to check the device
// ------------------------------------------
// Check if the "mobile" word exists in User-Agent 
$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 

// Check if the "tablet" word exists in User-Agent 
$isTab = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "tablet")); 

// Platform check  
$isIPad = false;
$isIOS = false;
$isIPhone = false;

$isWin = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "windows")); 
$isAndroid = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "android")); 
if (!$isWin) {

    $isIPhone = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "iphone")); 
    if (!$isIPhone) $isIPhone = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "applewebkit")); 
    $isIPad = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "ipad")); 
    $isIOS = $isIPhone || $isIPad; 
}
// ------------------------------------------

// ------------------------------------------
// Set up default session settings
// ------------------------------------------
$sql =  "select session, value from diet_settings";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $_SESSION[$row['session']] = $row['value'];
}
