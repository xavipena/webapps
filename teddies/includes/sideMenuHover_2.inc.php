<?php
/* 
  Side menu with hover
  Include 2 / 3
  menu functions to display content
*/
// ++ SideMenu
function GetMenu($db, $page, $extraText = "") {

    $output = "";
    $sql = "select * from diet_menu where IDmenu = $page and lang = '".$GLOBALS['lang']."'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $url = "./".$row['page'];
        $output .= "<li><a href='$url'>".$row['name'].$extraText."</a></li>";
    }
    return $output;
}

function LeftMenuShowNext ($row) {

    if (empty($row)) return "";

    $output = "";
    $domain = WEB_URL."/";
    $output .= "<li>";
    //if (substr($row['phpPage'],0,4) == "java") $domain ="";
    $class = $row['isApp'] == 1 && $row['isDone'] == 0 ? "style='color:red'" : "";
    $output .= "<a $class href='".$domain.$row['phpPage']."'>".$row['name']."</a></li>";
    return $output;
}

function ShowUser() {

    $user = $GLOBALS["lang"] == "ca" ? "cap" : "ninguno";
    $output = "";
    if (isset($_SESSION['diet_user'])) 
    {
        if ($_SESSION['diet_user'] != "") 
        {
            $user = empty($_SESSION['username']) ? "" : $_SESSION['username'];
            if ($user != "") {

                //$text = $GLOBALS["lang"] == "ca" ? "Usuari" : "Usuario";
                //$output .= "<li><a href='../diet/xDietUser_1.php'>$text $user</a></li>";
                $output .= GetMenu($GLOBALS["db"], 2, " ".$user);
                $output .= GetMenu($GLOBALS["db"], 50); 
            }
        }
    }
    else {
        
        //$output .= "<li><a href='../diet/login/login.php'>Log In</a></li>";
        $output .= GetMenu($GLOBALS["db"], 48); 
    }
    return $output;
}

function DietCommon() {

    $text = $GLOBALS['lang'] == "ca" ? "Inici" : "Inicio";
    $output = "<li><a href='../diet/xDietCover.php'>$text</a></li>";
    if (isset($_SESSION['diet_user'])) 
    {
        if ($_SESSION['diet_user'] != "") 
        {
            $output .= "<li><a href='../diet/xDiet.php'>Menú</a></li>";
        }
    }        
    return $output;
}

function LeftMenuPublic($page) {

    $server = "https://diaridigital.net";
    $output = "<li><a href='$server/apps.php'>Apps</a></li>";
    
    switch ($page)
    {
        case INNER_PAGE:

            $output .= "<li><a href='$server/photographers'>Inici</a></li>";
            break;
    }
    return $output;
}

function LeftMenu($db, $app) {

    $sql = "select * from link_pages where IDpage = $app and isPublic = 1";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)){

        $server = "https://diaridigital.net";
        $output = "<li><a href='$server/apps.php'>Apps</a></li>";
    }
    else {
    
        $output  = "<li><a href='../apps/googleApps.php'>Apps</a></li>";
    }

    if (!empty($_SESSION['diet_user'])) {
        
        $output .= "<li><a href='../x/logout.php'>Logout</a></li>";
    }
    
    switch ($app)
    {
        case PAGE_APPS:

            $sql = "select * from link_pages where isApp = 1 and isDone = 1 order by name";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) 
            {
                $output .= "<li><a href='../apps/googleAppsGo.php?page=".$row['IDpage']."'>".$row['name']."</a></li>";
            }
            break;

        case PAGE_TEDDIES:
            $output .= "<li><a href='cover.php'>Portada</a></li>";
            $output .= "<li><a href='main.php'>Catàleg</a></li>";
            $output .= "<li><a href='stories.php'>Aventures</a></li>";
            $output .= "<li><a href='stuff.php'>Coses</a></li>";
            $output .= "<li><a href='logout.php'>Surt</a></li>";
            break;
    }
    return $output;
}
// -- SideMenu
