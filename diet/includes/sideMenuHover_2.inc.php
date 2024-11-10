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

    if ($GLOBALS['page'] == "diet") {

        $text = $GLOBALS['lang'] == "ca" ? "Cerca el lloc" : "Busca en sitio";
        $output .= "<li><a href='../diet/xDietSearchsite.php'>$text</a></li>";
    }
    else {

        $output .= "<li><a href='../x/searchsite.php'>Cerca el lloc</a></li>";
    }

    switch ($app)
    {
        case PAGE_BLOCS:

            $blog = empty($GLOBALS['$blog']) ? "" : $GLOBALS['$blog'];
            $output .= "<li><a href='../blocs/googleAdmin.php'>Inici</a></li>";
            $output .= "<li><a href='../blocs/googleAdminSelector.php?blog=$blog'>Selector</a></li>";
            $output .= "<li><a href='../blocs/googleAdminEdit.php'>Edició</a></li>";
            break;

        case PAGE_BLOGS:

            $blog = empty($GLOBALS['$blog']) ? "" : $GLOBALS['$blog'];
            $output .= "<li><a href='../blogs/googleBlogStart.php'>Inici</a></li>";
            $output .= "<li><a href='../blogs/googleBlogUtilities.php?menu=2'>Edició</a></li>";
            $output .= "<li><a href='../blogs/googleBlogIndex.php'>Llistat d'articles</a></li>";
            $output .= "<li><a href='../blogs/googleBlogCalendar.php'>Publicació</a></li>";
            $output .= "<li><a href='../blogs/googleBlogUtilities.php?menu=1'>Utilitats</a></li>";
            $output .= "<li><a href='../projects/googleProjectsProperties.php'>Estat dels projectes</a></li>";
            $output .= "<li><a href='../projects/googleProjectsEnhancements.php'>Millores</a></li>";
            break;

        case PAGE_HOME:

            $output .= "<li><a href='../x/newLink.php'>Nou</a></li>";
            $output .= "<li><a href='../x/google.php'>Home</a></li>"; 
            $output .= "<li><a href='../x/bookmarks.php'>Bookmarks</a></li>"; 
            $output .= "<li><a href='../x/googleWork.php'>Work</a></li>"; 
            $output .= "<li><a href='../x/logout.php'>Logout</a></li>";
            
            $c = 0; $a = 0;
            $sql = "select * from link_pages where isApp = 0 order by position";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {
                $output .= LeftMenuShowNext($row);
                $c += 1;
            }
            break;

        case PAGE_APPS:

            $sql = "select * from link_pages where isApp = 1 and isDone = 1 order by name";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) 
            {
                $output .= "<li><a href='../apps/googleAppsGo.php?page=".$row['IDpage']."'>".$row['name']."</a></li>";
            }
            break;

        case PAGE_QUOTES:

            $sql = "select * from link_sections where IDpage = 100";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) 
            {
                $url = "../quotes/googleQuotesList.php?sc=".$row['IDsection'];
                $output .= "<li><a href='$url'>".$row['name']."</a></li>";
            }
            break;
            
        // ---------------------------------------------------------------------------------
        // ++Diet app
        // ---------------------------------------------------------------------------------
        case PAGE_DIET_COVER:

            $output .= ShowUser();
            $output .= DietCommon();
            $output .= "<li><a href='../diet/xDietTopics.php?id=9'>Mites</a></li>";
            $output .= "<li><a href='../diet/apps/menu.php'>Mòbil</a></li>";
            break;

        case PAGE_DIET_MENU:
        case PAGE_DIET:
        case PAGE_DIET_GLOSSARY:
        
            $output .= ShowUser();
            $output .= DietCommon();
            $output .= GetMenu($db, 1);
            
            $new = $GLOBALS["lang"] == "ca" ? "> Nou" : "> Nuevo";
            switch ($GLOBALS['sourceTable']) {
            
                case "diet_products":
            
                    $output .= "<li><a href='../diet/xDietProduct_1.php'>$new</a></li>";
                    break;
            
                case "diet_user_meals":
            
                    $output .= "<li><a href='../diet/xDietDay_1.php'>$new</a></li>";
                    break;
            
                case "diet_dishes":
            
                    $output .= "<li><a href='../diet/xDietDishes_1.php'>$new</a></li>";
                    break;
            }
        
            $sql =  "select * from diet_menu where status = 'A' and lang = '".$GLOBALS["lang"]."' and IDmenu > 0 ".
                    "and (isMenu = 1 or isSelector = 1) order by sequence";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) 
            {
                $url = "./".$row['page'];
                $output .= "<li><a href='../diet/$url'>".$row['name']."</a></li>";
            }
            break;

        case PAGE_DIET_AUDIT:

            $output .= DietCommon();
            $sql = "select * from diet_menu where IDsection = ".PAGE_DIET_AUDIT." and lang = '".$GLOBALS["lang"]."'";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) {
            
                $output .= "<li><a href='../diet/".$row['page']."'>".$row['name']."</a></li>";
            }
            break;
        // ---------------------------------------------------------------------------------
        // --Diet app
        // ---------------------------------------------------------------------------------

        case PAGE_CSJ:

            $output .= "<li><a href='googleCSJ.php'>Inici</a></li>";
            $output .= "<li><a href='googleCSJList.php'>Llistat</a></li>";
            $output .= "<li><a href='googleCSJCheckList.php'>Checklist</a></li>";
            $output .= "<li><a href='googleCSJNotes_1.php?year=".$_SESSION['year']."'>Nova nota</a></li>";
            $output .= "<li><a href='googleCSJInfoMenu.php'>Info</a></li>";
            $output .= "<li><a href='../pdf/Guia_de_emergencias.pdf'>Guia d'emergències</a></li>";
            break;

        case PAGE_STORIES:

            $sql =  "select * from ph_review_cats order by name";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {
                $output .= "<li><a href='googleStoriesDetails.php?st=".$row['IDcat']."'>".$row['name']."</a></li>";
            }

            break;

        case PAGE_SOFTWARE:

            $output .= "<li><a href='googleSoftware.php'>Inici</a></li>";
            if (!empty($_SESSION['runner_id'])) 
            {
                $output .= "<li><a href='newSoftware.php'>[Nou]</a></li>";
            }
            $output .= "<li><a href='javascript:doSearch()'>Cerca</a></li>";
            $output .= "<li><a href='aboutSoftware.php'>Definicions</a></li>";
            $output .= "<li><a href='googleSoftwareDir.php'>Directoris</a></li>";
            $output .= "<li><a href='googleSoftwareDistros.php'>Linux</a></li>";
            $output .= "<li><a href='googleResources.php'>Recursos</a></li>";
            $output .= "<li><a href='googleBusiness.php'>Business</a></li>";
            break;

        case PAGE_SCIFI:

            $output .= "<li><a href='googleSciFi.php?key=sy'>Sistemas</a></li>";
            $output .= "<li><a href='googleSciFi.php?key=pl'>Planets</a></li>";
            $output .= "<li><a href='googleSciFi.php?key=sh'>Ships</a></li>";
        
            break;

        case PAGE_ELECTRIC:

            $output .= "<li><a href='googleElectric.php'>Inici</a></li>";
            $output .= "<li><a href='googleElectricPower.php'>La potència</a></li>";
            $output .= "<li><a href='googleElectricConsumption.php'>El consum</a></li>";
            
            break;
        
        case PAGE_MAGAZINE:

            $output .= "<li><a href='googleMagazines.php'>Catàleg</a></li>";
            $output .= "<li><a href='https://www.fotodng.com/'>DNG</a></li>";
            $output .= "<li><a href='https://inteltechniques.com/magazine.html'>UNREDACTED</a></li>";
            $output .= "<li><a href='googleCatalog.php?mag=3'>eBooks</a></li>";
            break;

        case PAGE_CHANNELS:
            $output = "<li><a href='https://diaridigital.net'>Portada</a></li>";
            break;

        case PAGE_TEDDIES:
            $output .= "<li><a href='cover.php'>Portada</a></li>";
            $output .= "<li><a href='main.php'>Catàleg</a></li>";
            $output .= "<li><a href='stories.php'>Aventures</a></li>";
            $output .= "<li><a href='stuff.php'>Coses</a></li>";
            break;
    }
    if ($GLOBALS['page'] == "diet") {

        $output .= "<li><a href='../diet/xDietGlossary.php'>Glossary</a></li>";
        if (!empty($_SESSION['diet_user'])) {

            if ($_SESSION['diet_user'] == 1) {
                
                $output .= "<li><a href='../diet/xDietTesting.php'>Proves</a></li>";
            }
        }
    }
    return $output;
}
// -- SideMenu
