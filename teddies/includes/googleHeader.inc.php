<?php
/**
 *
 * @author xavipena
 * 
 */
    define('GALLERY_PAGE', 'gallery');

    if (empty($page)) $page = "phaApps"; // default
    switch ($page) { 
        
        case "biblio":
            include '../bibliotk/includes/config.inc.php';
            break;
    
        default:
            include __DIR__.'/config.inc.php';
            break;
    }
    require_once __DIR__."/locale.lib.php";

    //----------------------------
    // check for session settings
    // if session is created
    //----------------------------
    if (session_status() === PHP_SESSION_ACTIVE) {

        // Apps with own login procedure
        //-----------------------------------------------------------------------------
        $goPrivateLogin = TRUE;
        switch ($page) {

            case "diet":
                $goPrivateLogin = FALSE;
                break;
        }
        if (empty($_SESSION['settings'])) 
        {
            if ($goPrivateLogin) {

                header("location: https://diaridigital.net/private.php?sr=3");
            }
        }
    }
    if (empty($_SESSION['settings'])) 
    {
        $_SESSION['settings'] = "Y";
        //----------------------------
        // load session settings
        //----------------------------
        $sql = "select * from project_settings";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $_SESSION[$row['session']] = $row['value'];
        }
    }
    $_SESSION['pageID'] = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>XaviPena</title> 
    <meta http-equiv="Content-Type" content="text/html"/>
    <meta charset="UTF-8">
<?php 
    $h_description = "Xavi Peña";

    //----------------------------
    // load app description for title
    //----------------------------
    $sql = "select * from apps where page = '$page'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $h_description = $row['title'];
    }
?>
    <meta name="description" content="<?php echo $h_description?>">
    <meta name="abstract" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex,nofollow">
    <meta name="lang" content="<?php echo $lang ?>_ES">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<?php
    if (!empty($useMaps) && $useMaps == "Y") {
?>
    <link rel="stylesheet" href="../css/ol.css" type="text/css">
    <style>
      .map {
        height: 400px;
        width: 100%;
      }
    </style>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/ol.js" type="text/javascript"></script>
<?php
    }
?>
<?php
// ------------------------------------------
// Part from googleHeader.inc.php moved to sideMenuHover_1.inc.php
// To allow css hierarchy override side menu styles
// ------------------------------------------
/*
    if ($page == GALLERY_PAGE) { // galleries

        echo "<link rel='stylesheet' type='text/css' href='css/reset.css'>";
        echo "<link rel='stylesheet' type='text/css' href='css/style.css'>";
        echo "<link rel='stylesheet' type='text/css' href='css/zoom.css'>";
    }
    else {

        echo "<link rel='stylesheet' type='text/css' href='../css/google.css' />";
        echo "<link rel='stylesheet' type='text/css' href='../css/card.css' />";
        $cssPage = $page;
        if (!file_exists("../css/$cssPage.css")) $cssPage = "diet";
        echo "<link rel='stylesheet' type='text/css' href='../css/$cssPage.css' />";
        if (file_exists("./css/$page.css")) {
            
            echo "<link rel='stylesheet' type='text/css' href='./css/$page.css' />";
        }
    }
    if ($page == GALLERY_PAGE) { // galleries
?>
        <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-beta.2/lazyload.js"></script>
        <script src="js/transition.js"></script>
        <script src="js/zoom.min.js"></script>
        <script src="js/gallery.js"></script>
<?php  
    }
    else {

        echo "<script src='../js/jquery-3.3.1.min.js'></script>";
        echo "<script src='../js/google.js'></script>";
        if (file_exists("./js/$page.js")) {
            
            echo "<script src='./js/$page.js'></script>";
        }
    }    
*/
    if (!empty($useCharts) && $useCharts == "Y") {
?>
        <script type="text/javascript" src="../../js/chart.min.js"></script>
        <script type="text/javascript" src="../../js/chart-utils.min.js"></script>
<?php
    }
?>

